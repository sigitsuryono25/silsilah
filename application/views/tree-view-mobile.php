<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <meta http-equiv="X-UA-Compatible" content="ie=edge">
        <meta name="Description" content="Enter your description here"/>
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/4.5.0/css/bootstrap.min.css">
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.13.1/css/all.min.css">
        <link rel="stylesheet" href="<?php echo base_url() ?>assets/css/style.css">
        <title>Family Tree View</title>
    </head>
    <body>
        <!--<div class="container-fluid p-5">-->            
            <div class="bg-success mt-4" style="width: 300%; background: green">
                <div class="mainContainer"></div>
            </div>
        <!--</div>-->



        <script type="text/javascript" src="http://ajax.googleapis.com/ajax/libs/jquery/1.4.4/jquery.min.js"></script>
        <script>
            let testImgSrc = "https://ssl.gstatic.com/images/branding/product/1x/avatar_circle_blue_64dp.png";
            var xmlhttp = new XMLHttpRequest();
            var url = "<?php echo site_url('silsilah/getFamilyTree') ?>";

            xmlhttp.onreadystatechange = function () {
                if (xmlhttp.readyState == 4 && xmlhttp.status == 200) {
                    myFunction(xmlhttp.responseText);
                }
            }
            xmlhttp.open("GET", url, true);
            xmlhttp.send();


            function myFunction(parentId) {
                var members = JSON.parse(parentId);
                for (var i = 0; i < members.length; i++) {
                    var member = members[i];
                    if (member.parentId === members[i].parentId) {
                        var parent = members[i].parentId ? $("#containerFor" + members[i].parentId) : $(".mainContainer"),
                                memberId = member.memberId,
                                metaInfo = members[i].sebagai;
                        if (i == 0) {
                            //f8c6e2
                            if(members[i].jk == "Laki-Laki"){
                                parent.append("<div class='container-member' id='containerFor" + memberId + "'>" +
                                    "<div class='root border p-2 laki'><img  src='" + testImgSrc + "'/><br>" +
                                    "<a class='text-decoration-none'><span class='name font-weight-bold'>" +
                                    members[i].nama + "</span><br><small class='name'>" + members[i].gelar + "</small><br><small class='name'>" + members[i].berkuasa_pada + "</small><br><span class='name'><i>" + members[i].sebagai + "</i></span></a><br>" +
                                    "<div class='metaInfo'>" + metaInfo + "</div></div></div>");
                            }else{
                                parent.append("<div class='container-member ' id='containerFor" + memberId + "'>" +
                                    "<div class='root border p-2 perempuan'><img src='" + testImgSrc + "'/><br>" +
                                    "<a class='text-decoration-none'><span class='name font-weight-bold'>" +
                                    members[i].nama + "</span><br><small class='name'>" + members[i].gelar + "</small><br><small class='name'>" + members[i].berkuasa_pada + "</small><br><span class='name'><i>" + members[i].sebagai + "</i></span></a><br>" +
                                    "<div class='metaInfo'>" + metaInfo + "</div></div></div>");
                            }
                        } else {
//                            parent.append("<div class='container-member' id='containerFor" + memberId + "'>" +
//                                    "<div class='member border p-2'><img src='" + testImgSrc + "'/><br><a class='name' href='update.php?m_id=" + members[i].memberId + "'>" + members[i].nama + "</a><br><div class='metaInfo'>" + metaInfo + "</div></div></div>");
                            if(members[i].jk == "Laki-Laki"){
                                parent.append("<div class='container-member' id='containerFor" + memberId + "'>" +
                                        "<div class='member border p-2 laki'><img class='d-none' src='" + testImgSrc + "'/><br>" +
                                        "<a class='text-decoration-none'><span class='name font-weight-bold'>" +
                                        members[i].nama + "</span><br><small class='name'>" + members[i].gelar + "</small><br><small class='name'>" + members[i].berkuasa_pada + "</small><br><span class='name'><i>" + members[i].sebagai + "</i></span></a><br>" +
                                        "<div class='metaInfo'>" + metaInfo + "</div></div></div>");
                            }else{
                                parent.append("<div class='container-member' id='containerFor" + memberId + "'>" +
                                        "<div class='member border p-2 perempuan'><img class='d-none' src='" + testImgSrc + "'/><br>" +
                                        "<a class='text-decoration-none'><span class='name font-weight-bold'>" +
                                        members[i].nama + "</span><br><small class='name'>" + members[i].gelar + "</small><br><small class='name'>" + members[i].berkuasa_pada + "</small><br><span class='name'><i>" + members[i].sebagai + "</i></span></a><br>" +
                                        "<div class='metaInfo'>" + metaInfo + "</div></div></div>");
                            }
                        }
                        myFunction(memberId);
                    }
                }
            }
            (null);
        </script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.16.1/umd/popper.min.js"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/4.5.0/js/bootstrap.min.js"></script>
    </body>
</html>
