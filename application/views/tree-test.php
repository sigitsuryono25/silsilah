
<!DOCTYPE html>
<html>
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>Silsilah Bugis Makasar</title>
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/4.5.0/css/bootstrap.min.css">
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.13.1/css/all.min.css">
        <link rel="stylesheet" type="text/css" href="<?php echo base_url() ?>assets/css/silsilah.css">
    </head>
    <body>
        <div class="container-fluid">
            <div class="outer-wrapper">
                <div class="row-wrapper center">
                    <div class="main-wrapper main-head main-section">
                    </div>
                </div>
            </div>
        </div>

        <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.16.1/umd/popper.min.js"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/4.5.0/js/bootstrap.min.js"></script>

        <script>
            $(document).ready(function () {
                mainSection();
            });

            function mainSection() {
                var url = "<?php echo site_url('silsilah/test?id-node='. $this->input->get('id-node')) ?>";
                $.get(url, null, function (data) {
                    $(".main-section").html(data);
//                    $(".member-id").each(function () {
//                        childSection($(this).html());
//                    });
                });
            }
            function childSection(parentId) {
                var url = "<?php echo site_url('silsilah/anoterMember/') ?>" + parentId + "/false";
                $.get(url, null, function (data) {
                    $(".child-section").html(data);
                });
            }
        </script>
    </body>
</html>