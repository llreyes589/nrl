@php
    $steps = [
        ['Gather Required Information and Documents:', 'This might include personal details, contact information, payment information, and any specific documentation required for the registration process.'],
        ['Complete the Application Form:', 'This can be done online or by filling out a physical form, depending on the registration method.'],
        ['Provide Payment Information:', 'If a fee is required, provide the necessary payment details, such as credit card information or bank details.'],
        ['Submit the Application:', 'Once the application is complete and payment (if applicable) has been made, submit it via the designated method, whether online or by mail.'],
        ['Confirmation and Follow-up:', 'After submitting the application, you should receive a confirmation, which might include details about next steps or a receipt.'],

    ];
@endphp

<div class="container my-2">
    <p class="lead">General Steps in Registration:</p>
    <ol >
        @foreach ($steps as $step)
        
        <li>
            <strong>{{ $step[0] }}</strong>
            <p>{{ $step[1] }} </p>
        </li>
        @endforeach
    </ol>

    <div class="d-flex justify-content-end ">

        <button type="button" class="btn btn-primary" onclick="handleChangePage()">Back to Login</button>
    </div>
</div>