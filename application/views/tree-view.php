<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="UTF-8">
        <!--<meta name="viewport" content="width=device-width, initial-scale=1.0">-->
        <meta http-equiv="X-UA-Compatible" content="ie=edge">
        <meta name="Description" content="Enter your description here"/>
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/4.5.0/css/bootstrap.min.css">
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.13.1/css/all.min.css">
        <link rel="stylesheet" href="<?php echo base_url() ?>assets/css/style.css">
        <title>Family Tree View</title>
    </head>
    <body>
        <div class="container-fluid">
            <div class="row">
                <div class="col-md-12"><a class="btn btn-block btn-primary" href="<?php echo site_url('silsilah/addmember') ?>">Add Member Family</a></div>
            </div>
            <?php
            if ($this->agent->is_mobile()) {
                ?>
                <iframe class="border-0" src="<?php echo site_url('silsilah/showFamilyTreeMobile') ?>"style="width: 100%; height: 100vh"></iframe>
            <?php } else {
                ?>
                <iframe class="border-0" src="<?php echo site_url('silsilah/showFamilyTreeDesktop') ?>" style="width: 100%; height: 100vh"></iframe>
                    <?php
                }
                ?>
        </div>
    </body>
</html>
