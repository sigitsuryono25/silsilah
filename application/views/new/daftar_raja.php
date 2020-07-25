<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <meta http-equiv="X-UA-Compatible" content="ie=edge">
        <meta name="Description" content="Enter your description here"/>
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/4.5.0/css/bootstrap.min.css">
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.13.1/css/all.min.css">
        <link rel="stylesheet" href="assets/css/style.css">
        <title>Title</title>
    </head>
    <body>
        <div class="container">
            <div class="table-resposive p-5" >
                <div class="row my-3">
                    <div class="col-md-6">                
                        <a class="btn btn-sm" onclick="window.history.back()">Kembali</a>
                    </div>
                    <div class="col-md-6 text-right text-white">
                        <a class="btn btn-primary btn-sm" href="<?= site_url('silsilah/newAddMember?id-node=' . $this->uri->segment(3)) ?>">Tambahkan Silsilah</a>
                    </div>
                </div>
                <table class="table table-striped table-bordered">
                    <thead>
                        <tr>
                            <th>No</th>
                            <th>Nama Raja</th>
                            <th>Lama Berkuasa</th>
                            <th>Tindakan</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($list_raja as $key => $raja) { ?>
                            <tr>
                                <td><?= $key + 1 ?></td>
                                <td><?= $raja->nama ?></td>
                                <td><?= $raja->berkuasa_pada ?></td>
                                <td>
                                    <a class="btn btn-sm btn-primary" href="<?= site_url('silsilah/treetest?id-node=' . $raja->id_node) ?>" target="_blank">Lihat Silsilah</a>
                                </td>
                            </tr>
                        <?php }
                        ?>
                    </tbody>
                </table>
            </div>
        </div>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.16.1/umd/popper.min.js"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/4.5.0/js/bootstrap.min.js"></script>
    </body>
</html>