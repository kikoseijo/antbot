@if (session()->has('message'))
        <script type="text/javascript">
            document.addEventListener('DOMContentLoaded', function () {
                Toast.fire({
                    icon:'success',
                    title:'{{ session('message') }}'
                });
            });
        </script>
@endif
@if (session()->has('error'))
        <script type="text/javascript">
            document.addEventListener('DOMContentLoaded', function () {
                Toast.fire({
                    icon:'error',
                    title:'{{ session('error') }}'
                });
            });
        </script>
@endif
