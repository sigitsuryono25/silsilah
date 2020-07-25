$(document).ready(function () {

                $.get("<?php echo site_url('silsilah/getfamilytree/') ?>", null, function (data) {
                    setToView(data);
                }, "JSON")
            });

            function setToView(data) {
                for (var i = 0; i < data.length; i++) {
                    var memberId = data[i].memberId;
                    var parentId = data[i].parentId;
                    var nama = data[i].nama;
                    var sebagai = data[i].sebagai;
                    var berkuasa_pada = data[i].berkuasa_pada;
                    var jk = data[i].jk;
                    var gelar = data[i].gelar;

                        var root = $(".root");
                        if (sebagai === "Raja") {
                            var appended = 
                                    ` <li class="with-pict king male">
                                        <div class="pict-wrapp">
                                            <img src="<?php echo base_url() ?>assets/img/male.jpeg" alt="${sebagai}">
                                        </div>
                                        <h3>${nama}</h3>
                                        <p class="label-datu">${gelar}</p>
                                        <p class="year">${berkuasa_pada}</p>
                                        <p class="label-title">${sebagai}</p>
                                    </li>`;
                            root.append(appended);                            
                        }else if(sebagai === "Ratu"){
                            var appended = `<li class="queen female">
                                                <h3>${nama}</h3>
                                                <p class="label-datu">${gelar}</p>
                                                <p class="label-title">${sebagai}</p>
                                            </li>`;
                             root.append(appended);
                        }else{
                            
                        }
                    }
            }/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */


