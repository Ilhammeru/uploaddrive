<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <!-- jquery -->
    <script src="<?= site_url(); ?>jquery/dist/jquery.min.js"></script>

    <title>Download database</title>
</head>

<body>
    <h1 style="text-align: center;">Download database</h1>

    <script>
        var runDownload = setInterval(getDatabase, 1000);

        function addZero(data) {
            if (data < '10') {
                var newData = '0' + data;
            } else {
                var newData = data;
            }

            return newData;
        }

        function getDatabase() {
            var time = new Date()
            var year = time.getFullYear()
            var month = addZero((time.getMonth()) + 1)
            var day = addZero(time.getDate())
            var hour = time.getHours()
            var minutes = time.getMinutes()
            var sec = time.getSeconds()

            if (hour == 10 && minutes == 04) {
                if (hour == 10 && minutes == 04 && sec == 1) {
                    clearInterval(runDownload)
                } else {
                    $.ajax({
                        type: 'post',
                        url: '<?= site_url('database/putDatabase'); ?>',
                        dataType: 'text',
                        success: function(response) {
                            console.log(response)
                        }
                    })
                }
            }

            return false;
        }
    </script>

</body>

</html>