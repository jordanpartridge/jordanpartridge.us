<?php

namespace App\Filament\Resources;

use App\Filament\Resources\RidesResource\Pages\CreateRides;
use App\Filament\Resources\RidesResource\Pages\EditRides;
use App\Filament\Resources\RidesResource\Pages\ListRides;
use App\Models\Ride;
use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\Grid;
use Filament\Forms\Components\Group;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Support\Enums\FontWeight;
use Filament\Tables\Actions\Action;
use Filament\Tables\Actions\ActionGroup;
use Filament\Tables\Actions\DeleteAction;
use Filament\Tables\Actions\DeleteBulkAction;
use Filament\Tables\Actions\EditAction;
use Filament\Tables\Columns\ImageColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\Filter;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;

class RidesResource extends Resource
{
    protected static ?string $model = Ride::class;

    protected static ?string $navigationIcon = 'heroicon-o-rocket-launch';

    protected static ?string $navigationGroup = 'Activity Tracking';

    protected static ?int $navigationSort = 1;

    protected static ?string $recordTitleAttribute = 'name';

    public static function getNavigationBadge(): ?string
    {
        return static::getModel()::count();
    }

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Grid::make(['default' => 1, 'sm' => 3])->schema([
                    Section::make('Ride Details')
                        ->description('Basic information about your ride')
                        ->schema([
                            TextInput::make('name')
                                ->label('Ride Name')
                                ->required()
                                ->maxLength(255)
                                ->placeholder('Morning Ride')
                                ->columnSpan('full'),
                            DateTimePicker::make('date')
                                ->label('Ride Date & Time')
                                ->required()
                                ->timezone('America/New_York')
                                ->displayFormat('M j, Y g:i A')
                                ->columnSpan('full'),
                            TextInput::make('map_url')
                                ->label('Map URL')
                                ->required()
                                ->formatStateUsing(fn ($state) => str_replace(['https://', 'rides/'], '', $state))
                                ->dehydrateStateUsing(fn ($state) => 'rides/' . $state)
                                ->prefix('rides/')
                                ->placeholder('a12869707219.png')
                                ->columnSpan('full')
                                ->helperText('Just the filename is needed - the path will be added automatically'),
                        ])
                        ->columnSpan(2),

                    Group::make()
                        ->schema([
                            Section::make('Primary Metrics')
                                ->description('Key stats from your ride')
                                ->icon('heroicon-o-chart-bar')
                                ->schema([
                                    TextInput::make('distance')
                                        ->label('Distance')
                                        ->required()
                                        ->numeric()
                                        ->minValue(0)
                                        ->step(0.1)
                                        ->suffix('miles')
                                        ->hint('Total distance covered'),

                                    TextInput::make('average_speed')
                                        ->label('Average Speed')
                                        ->required()
                                        ->numeric()
                                        ->minValue(0)
                                        ->step(0.1)
                                        ->suffix('mph')
                                        ->hint('Overall average speed'),
                                ])
                                ->columns(1)
                                ->collapsible(),

                            Section::make('Additional Metrics')
                                ->description('Supplementary ride data')
                                ->icon('heroicon-o-clipboard-document-list')
                                ->schema([
                                    TextInput::make('max_speed')
                                        ->label('Max Speed')
                                        ->numeric()
                                        ->minValue(0)
                                        ->step(0.1)
                                        ->suffix('mph')
                                        ->hint('Highest recorded speed'),
                                    TextInput::make('calories')
                                        ->label('Calories')
                                        ->numeric()
                                        ->minValue(0)
                                        ->suffix('kcal')
                                        ->hint('Estimated calories burned'),
                                    TextInput::make('elevation')
                                        ->label('Elevation Gain')
                                        ->numeric()
                                        ->minValue(0)
                                        ->suffix('m')
                                        ->hint('Total elevation gained'),
                                ])
                                ->columns(1)
                                ->collapsible(),
                        ])
                        ->columnSpan(1),
                ]),
            ])
            ->columns(3);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                ImageColumn::make('map_url_signed')
                    ->disk('s3')
                    ->label('Route')
                    ->toggleable()
                    ->height(100) // Adjust this value to your preference
                    ->width(150)  // Maintaining a good aspect ratio
                    ->square(false) // Allows non-square aspect ratio
                    ->extraImgAttributes(['class' => 'object-cover rounded-lg']),
                TextColumn::make('name')
                    ->label('Ride Name')
                    ->searchable()
                    ->toggleable()
                    ->sortable()
                    ->weight(FontWeight::Bold)
                    ->description(
                        fn (Ride $record): string => $record->date->format('M j, Y g:i A')
                    )
                    ->icon('heroicon-o-map'),
                TextColumn::make('distance')
                    ->label('Distance')
                    ->suffix(' mi')
                    ->toggleable()
                    ->sortable()
                    ->numeric(
                        decimalPlaces: 1,
                        decimalSeparator: '.',
                        thousandsSeparator: ','
                    )
                    ->description(
                        fn (Ride $record): string => number_format($record->average_speed, 1) . ' Mph avg'
                    )
                    ->icon('heroicon-o-map-pin'),

                TextColumn::make('elevation')
                    ->label('Elevation')
                    ->suffix(' m')
                    ->toggleable()
                    ->numeric()
                    ->sortable()
                    ->description(
                        fn (Ride $record): string => ($record->max_speed ? $record->max_speed . ' km/h max' : 'No max speed')
                    )
                    ->icon('heroicon-o-arrow-trending-up'),
            ])
            ->defaultSort('date', 'desc')
            ->filters([
                Filter::make('recent_rides')
                    ->label('Recent Rides')
                    ->query(fn (Builder $query) => $query->where('date', '>=', now()->subDays(7))),
                SelectFilter::make('distance_range')
                    ->label('Distance Range')
                    ->options([
                        '0-5'  => 'Short (0-5 km)',
                        '5-15' => 'Medium (5-15 km)',
                        '15+'  => 'Long (15+ km)',
                    ])
                    ->query(function (Builder $query, array $data) {
                        if (empty($data['value'])) {
                            return $query;
                        }

                        return match ($data['value']) {
                            '0-5'  => $query->whereBetween('distance', [0, 5]),
                            '5-15' => $query->whereBetween('distance', [5, 15]),
                            '15+'  => $query->where('distance', '>', 15),
                        };
                    }),
            ])
            ->actions([
                ActionGroup::make([
                    EditAction::make()
                        ->icon('heroicon-o-pencil'),
                    DeleteAction::make()
                        ->icon('heroicon-o-trash'),
                ]),
            ])
            ->bulkActions([
                DeleteBulkAction::make()
                    ->icon('heroicon-o-trash'),
            ])
            ->emptyStateIcon('heroicon-o-bicycle')
            ->emptyStateHeading('No rides recorded')
            ->emptyStateDescription('Start tracking your bike rides to see them appear here.')
            ->emptyStateActions([
                Action::make('create')
                    ->label('Record New Ride')
                    ->url(route('filament.admin.resources.rides.create'))
                    ->icon('heroicon-o-plus'),
            ]);
    }

    public static function getRelations(): array
    {
        return [
            // Add any relations here if needed
        ];
    }

    public static function getPages(): array
    {
        return [
            'index'  => ListRides::route('/'),
            'create' => CreateRides::route('/create'),
            'edit'   => EditRides::route('/{record}/edit'),
        ];
    }

    public static function getGloballySearchableAttributes(): array
    {
        return ['name', 'date'];
    }
}
