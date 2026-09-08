@if (session('success'))
    <div class="mb-4 p-4 border border-success bg-success/5 text-success rounded font-semibold">
        {{ session('success') }}
    </div>
@endif

@if (session('error'))
    <div class="mb-4 p-4 border border-danger bg-danger/5 text-danger rounded font-semibold">
        {{ session('error') }}
    </div>
@endif

@if (session('warning'))
    <div class="mb-4 p-4 border border-warning bg-warning/5 text-warning rounded font-semibold">
        {{ session('warning') }}
    </div>
@endif

@if (session('info'))
    <div class="mb-4 p-4 border border-info bg-info/5 text-info rounded font-semibold">
        {{ session('info') }}
    </div>
@endif

@if ($errors->any())
    <div class="mb-4 p-4 border border-danger bg-danger/5 text-danger rounded font-semibold">
        <ul class="list-disc list-inside">
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const flashMessages = document.querySelectorAll('.mb-4.p-4.border');
        flashMessages.forEach(function(message) {
            setTimeout(function() {
                message.style.transition = 'opacity 0.5s ease';
                message.style.opacity = '0';
                setTimeout(function() {
                    message.remove();
                }, 500);
            }, 5000);
        });
    });
</script>
