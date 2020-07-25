<?php
$news_car = $this->crud->select_other('news', "INNER JOIN kategori ON news.id_kategori=kategori.id_kategori WHERE news.id_kategori IN('1') LIMIT 5")->result();
?>
<div class="container">
    <div class="row  px-4">
        <div class="col-md-12">
            <div id="gallery">
                <h2 class="title1" id="titleborder"><span>Kegiatan Terbaru</span></h2>
            </div>
        </div>
        <div class="col-md-12">
            <div id="newsIndicators" class="carousel slide" data-ride="carousel" data-interval="3000">
                <ol class="carousel-indicators">
                    <?php
                    foreach ($news_car as $key=>$c) {
                        ?>
                        <?php if ($key == 0) { ?>
                            <li data-target="#newsIndicators" data-slide-to="<?php echo $key ?>" class="active"></li>
                        <?php } else { ?>
                            <li data-target="#newsIndicators" data-slide-to="<?php echo $key ?>"></li>
                            <?php
                        }
                    }
                    ?>
                </ol>
                <div class="carousel-inner">
                    <?php
                    $i = 0;
                    foreach ($news_car as $c) {
                        ?>
                        <?php if ($i == 0) { ?>
                            <div class="carousel-item active " style='min-height: 200px'>
                            <?php } else { ?>
                                <div class="carousel-item" style='min-height: 200px'>
                                <?php }
                                ?>
                                <div class="row mb-3 px-2 d-block d-md-none">
                                    <div class="py-2 col-md-12 light-shadow text-center">            
                                        <div class="row" onclick="window.open(`<?php echo site_url('reader/' . $c->judul_seo) ?>`)" style="cursor: pointer">
                                            <div class="text-center col-md-2 d-block align-self-center justify-content-center">
                                                <img class="lazyload img-fluid" src="https://mir-s3-cdn-cf.behance.net/project_modules/disp/afb8cb36197347.5713616457ee5.gif" data-src="./news/<?php echo $c->foto_news ?>">
                                            </div>
                                            <div class="col-md-10">
                                                <div class="mb-1 text-primary">
                                                    <h5>
                                                        <b><?php echo $c->jdl_news ?></b>
                                                    </h5>
                                                </div>
                                                <p class="my-1 text-justify">
                                                    <?php
                                                    $string = strip_tags($c->ket_news);
                                                    if (strlen($string) > 150) {

                                                        // truncate string
                                                        $stringCut = substr($string, 0, 150);
                                                        $endPoint = strrpos($stringCut, ' ');

                                                        //if the string doesn't contain any space then it will cut without word basis.
                                                        $string = $endPoint ? substr($stringCut, 0, $endPoint) : substr($stringCut, 0);
                                                        $string .= '... <a class="badge badge-warning">Selengkapnya</a>';
                                                    }
                                                    echo $string;
                                                    ?>
                                                </p>
                                            </div>
                                        </div>
                                        <hr>
                                    </div>
                                </div>
                                <!--desktop-->
                                <div class="row mb-3 px-5  d-none d-md-block">
                                    <div class="py-2 col-md-12 light-shadow text-center">            
                                        <div class="row" onclick="window.open(`<?php echo site_url('reader/' . $c->judul_seo) ?>`)" style="cursor: pointer">
                                            <div class="text-center col-md-2 d-block align-self-center justify-content-center">
                                                <img class="lazyload img-fluid" src="https://mir-s3-cdn-cf.behance.net/project_modules/disp/afb8cb36197347.5713616457ee5.gif" data-src="./news/<?php echo $c->foto_news ?>">
                                            </div>
                                            <div class="col-md-10">
                                                <div class="mb-1 text-primary">
                                                    <h5>
                                                        <b><?php echo $c->jdl_news ?></b>
                                                    </h5>
                                                </div>
                                                <p class="my-1 text-justify">
                                                    <?php
                                                    $string = strip_tags($c->ket_news);
                                                    if (strlen($string) > 150) {

                                                        // truncate string
                                                        $stringCut = substr($string, 0, 150);
                                                        $endPoint = strrpos($stringCut, ' ');

                                                        //if the string doesn't contain any space then it will cut without word basis.
                                                        $string = $endPoint ? substr($stringCut, 0, $endPoint) : substr($stringCut, 0);
                                                        $string .= '... <a class="badge badge-warning">Selengkapnya</a>';
                                                    }
                                                    echo $string;
                                                    ?>
                                                </p>
                                            </div>
                                        </div>
                                        <hr>
                                    </div>
                                </div>
                            </div>
                            <?php
                            $i++;
                        }
                        ?>
                    </div>

                </div>
                <a class="news carousel-control-prev" href="#newsIndicators" role="button" data-slide="prev">
                    <span class="carousel-control-prev-icon text-primary" aria-hidden="true"></span>
                    <span class="sr-only">Previous</span>
                </a>
                <a class="news carousel-control-next" href="#newsIndicators" role="button" data-slide="next">
                    <span class="carousel-control-next-icon" aria-hidden="true"></span>
                    <span class="sr-only">Next</span>
                </a>
            </div>
        </div>
    </div>
</div>