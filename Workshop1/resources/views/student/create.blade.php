<!DOCTYPE html>
<html>
    <head>
        <title>Create Student</title>
    </head>

    <body>
        <h1>Create Student</h1>

        @if($errors->any())
            <div>
                <h3>Please fix the following errors:</h3>

                <ul>
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form action="{{ route('students.store') }}" method="POST">
            @csrf

            <div>
                <label for="name">Name</label>
                <input type="text" id="name" name="name" value="{{ old('name') }}">
                @error('name') <span style="color:red">{{ $message }}</span> @enderror
            </div>

            <br>

            <div>
                <label for="email">Email</label>
                <input type="email" id="email" name="email" value="{{ old('email') }}">
                @error('email') <span style="color:red">{{ $message }}</span> @enderror
            </div>

            <br>

            <div>
                <label for="phone">Phone</label>
                <input type="text" id="phone" name="phone" value="{{ old('phone') }}">
                @error('phone') <span style="color:red">{{ $message }}</span> @enderror
            </div>

            <br>

            <div>
                <label for="address">Address</label>
                <textarea id="address" name="address">{{ old('address') }}</textarea>
                @error('address') <span style="color:red">{{ $message }}</span> @enderror
            </div>

            <br>

            <div>
                <label for="date_of_birth">Date of Birth</label>
                <input type="date" id="date_of_birth" name="date_of_birth" value="{{ old('date_of_birth') }}">
                @error('date_of_birth') <span style="color:red">{{ $message }}</span> @enderror
            </div>

            <br>

            <button type="submit">Create Student</button>
        </form>

        <br>

        <a href="{{ route('students.index') }}">Back to Students</a>
    </body>
</html>