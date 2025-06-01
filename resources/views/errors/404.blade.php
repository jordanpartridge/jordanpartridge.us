@extends('errors::layout')

@section('title', __('Page Not Found'))

@section('message')
<div style="text-align: center;">
    <h1 style="font-size: 72px; font-weight: bold; margin: 0; color: #333;">404</h1>
    <p style="font-size: 24px; margin: 20px 0; color: #666;">Not Found</p>
    <p style="font-size: 16px; margin: 10px 0; color: #999;">The page you're looking for doesn't exist.</p>
    <div style="margin-top: 30px;">
        <a href="/" style="display: inline-block; padding: 12px 24px; background-color: #007bff; color: white; text-decoration: none; border-radius: 5px; font-weight: 600; transition: background-color 0.2s;">
            Go Home
        </a>
    </div>
</div>
@endsection
