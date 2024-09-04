@extends('main')
@section('main.content')
    <section class="d-flex justify-content-center align-items-center h-100">
        <form action="{{ route('vendor.sendapplication') }}" method="POST" class="vendor-application-form">
            @csrf
            <div class="logo">
                <a class="nav-logo d-flex align-items-center" href='#'>
                    <i class="fa-regular fa-lightbulb"></i>
                    <h2 class="m-0">
                        KBS 📖
                    </h2>
                </a>
            </div>
            <h4>Become Partner with Us!
            </h4>
            <label for="">
                Email:
            </label>
            @error('email')
                <span class="text-danger">
                    {{ $message }}
                </span>
            @enderror
            <input type="text" name="email" value="{{ old('email') }}">
            <label for="" class="mb-3">Provide information of what your products are and why you want to become
                partner</label>
            @error('application_letter')
                <span class="text-danger">
                    {{ $message }}
                </span>
            @enderror
            <textarea name="application_letter" cols="30" rows="10">{{ old('application_letter') }}</textarea>
            <button type="submit">Submit</button>
        </form>
    </section>
@endsection
@push('scripts')
    <script>
        @if (session('message'))
            const sessionMessage = "{{ session('message') }}";
        @else
            const sessionMessage = null;
        @endif

        if (sessionMessage) {
            toast('success', sessionMessage);
        }
    </script>
@endpush
