<a {{ $attributes->merge(['class' => 'btn-gradient']) }}>
    {{ $slot }}
</a>

<style>
    .btn-gradient {
        display: inline-block !important;
        padding: 0.75rem 1.5rem !important;
        font-size: 1rem !important;
        font-weight: 600 !important;
        text-align: center !important;
        color: #fff !important;
        background-image: linear-gradient(45deg, #6b00b6, #8e2de2, #4a00e0) !important;
        background-size: 200% 200% !important;
        border-radius: 10px !important;
        box-shadow: 0 4px 15px rgba(0, 0, 0, 0.25) !important;
        transition: all 0.4s ease !important;
        position: relative !important;
        overflow: hidden !important;
    }

    .btn-gradient:hover {
        background-position: right center !important;
        color: #fff !important;
        text-decoration: none !important;
        transform: translateY(-5px) !important;
        box-shadow: 0 8px 20px rgba(0, 0, 0, 0.35) !important;
    }

    .btn-gradient::before {
        content: '' !important;
        position: absolute !important;
        top: -50% !important;
        left: -50% !important;
        width: 200% !important;
        height: 200% !important;
        background: rgba(255, 255, 255, 0.1) !important;
        transition: all 0.4s ease !important;
        transform: rotate(45deg) !important;
    }

    .btn-gradient:hover::before {
        top: 0 !important;
        left: 0 !important;
        width: 100% !important;
        height: 100% !important;
    }

    .btn-gradient:active {
        transform: translateY(-2px) !important;
        box-shadow: 0 4px 10px rgba(0, 0, 0, 0.25) !important;
    }
</style>
