<?php 
/*
 * This file is part of WHATPANEL.
 *
 * @package     WHAT PANEL – Web Hosting Access Terminal Panel.
 * @copyright   2023-2024 Version Next Technologies and MadPopo. All rights reserved.
 * @license     BSL; see LICENSE.txt
 * @link        https://www.version-next.com
 */
use App\Models\User;
use App\Models\Client;
use App\Models\Order;
use App\Models\App;
use App\Models\Ticket;
use App\Libraries\AppLib;

?>
<small><?=lang('hd_lang.welcome_back')?> , <?php echo User::displayName(User::get_id_client());?> </small>
 <?php
            $user = User::get_id_client();
            $user_company = User::profile_info($user)->company;
            $cur = Client::client_currency($user_company);
             $client_paid = 0;
 
            if ($user_company > 0) {
                $client_paid = Client::client_amount_paid($user_company);

                $client_outstanding = Client::client_due_amount($user_company);

                $client_payments = Client::client_amount_paid($user_company);

                $client_payable = Client::client_payable($user_company);

                if ($client_payable > 0 && $client_payments > 0) {
                    $perc_paid = round(($client_payments/$client_payable) * 100,1);
                    $perc_paid = ($perc_paid > 100) ? '100' : $perc_paid;
                }else{
                    $perc_paid = 0;
                }
 
                ?>

            <?php } else {

                //$client_outstanding = $perc_paid = $client_payable = 0;
                $perc_open = 0;
                $client_outstanding = 0;
            }
            ?>


<div class="box-body">
<?= $this->extend('layouts/users') ?>

<?= $this->section('content') ?>

    <div class="row status_blocks p-3 client-dash-topBlocks">
            <div class="col-lg-3 col-sm-5">
                <div class="info-box client-domains bg-teal-gradient common-h py-3" >
                <a   href="<?= base_url() ?>domains">
                        <span class="info-box-icon px-1"><i class="fa fa-globe fa-1x "></i></span>
                        <span class="status_count px-1"><?=Order::client_domains($user_company);?></span>									 
                        <span class="label px-1"><?= lang('hd_lang.domains') ?> </span>										 					
                    </a>
                </div>
            </div>
            <div class="col-lg-3 col-sm-5">
                <div class="info-box client-accounts bg-green-gradient common-h py-3" >
                    <a  href="<?= base_url() ?>accounts">
                        <span class="info-box-icon px-1"><i class="fa fa-server fa-1x "></i></span>	
                        <span class="status_count px-1"><?=Order::client_accounts($user_company);?></span>									 
                        <span class="label px-1"><?= lang('hd_lang.accounts') ?> </span>
                    </a>					
                </div>
            </div>
            <div class="col-lg-3 col-sm-5">
                <div class="info-box client-invoices bg-light-blue common-h py-3" >
                    <a  href="<?= base_url() ?>invoices?view=unpaid">
                    <span class="info-box-icon px-1"><i class="fa fa-shopping-basket fa-1x"></i></span>										 
                    <span class="status_count px-1"><?=App::counter('invoices',array('client'=>$user_company,'status !='=>'Cancelled','status !='=>'Deleted','status'=>'Unpaid'));?></span>									 
                    <span class="label px-1"><?= lang('hd_lang.unpaid_invoices') ?> </span>							
                    </a>						
                </div>
            </div>
            <div class="col-lg-3 col-sm-5">
                <div class="info-box client-tickets bg-purple-gradient common-h py-3" >
                    <a  href="<?= base_url() ?>tickets">
                    <span class="info-box-icon px-1"><i class="fa fa-support fa-1x "></i></span>
                    <span class="status_count px-1"><?=App::counter('tickets',array('reporter'=>$user,'status !='=>'closed'));?></span>									 
                    <span class="label px-1"><?= lang('hd_lang.active_tickets') ?> </span>								
                    </a>
                </div>
            </div> 
    </div>

    <div class="row p-3">
        
        <div class="col-md-4"> 
            <section class="box box-default">
                <header class="box-header common-h"><?=lang('hd_lang.payments')?> </header>
                <div class="box-body text-center"> <h4 class='common-h'><small> <?=lang('hd_lang.paid_amount')?> :  </small>
                        <?php echo AppLib::format_currency(AppLib::client_currency($cur->code, Client::amount_paid($user_company)), $cur->code); ?></h4>
                    <small class="text-muted block common-p">
                        <?=lang('hd_lang.outstanding')?> : <?=AppLib::format_currency($client_outstanding, $cur->code)?>
                    </small>
                    <div class="inline position-relative">

                        <!-- <div class="easypiechart" data-percent="<?//= $perc_paid?>" data-line-width="16" data-loop="false" data-size="188">

                        </div> -->

                        <canvas id="client_chart" width="200" height="200" style='margin: 20px auto;' ></canvas>

                        <div class="donut-text-center common-p">
                        <span class=" step common-h h2"><?//= $perc_paid?></span>%
                        <div class="easypie-text common-p">
                            <?=lang('hd_lang.paid')?>
                        </div>
                        </div>

                    </div>
                </div>
                <div class="box-footer text-center common-p"><small><?=lang('hd_lang.total')?>:
                        <strong><?=AppLib::format_currency($client_payable, $cur->code)?></strong></small>
                </div> 
            </section>
        </div>

        <!-- Start Tickets -->
        <div class="col-md-8">
                <section class="box box-warning box-solid">
                    <header class="box-header with-border common-h p-3 text-white" style="background-color: orange;border-top-right-radius: 10px;border-top-left-radius: 10px;" >
                        <?=lang('hd_lang.active_accounts')?>
                    </header>
                    <div class="box-body">

                        <section class="slim-scroll" data-height="300" data-disable-fade-out="true" data-distance="0" data-size="5px" data-color="#333333" style='position: sticky;left: 0px;overflow: auto;width: auto;height: 300px;scrollbar-width: none;'>

                            <div class="list-group bg-white common-p px-3" style="border: 1px solid orange;border-radius: 0;position: sticky;top: 0px;overflow: auto;width: auto;height: 300px;scrollbar-width: none;border-bottom-left-radius: 10px;border-bottom-right-radius: 10px;" >
                                <?php
                                $orders = Order::client_orders($user_company);  
                                foreach ($orders as $key => $order) { 
                                    $earlier = new DateTime(Date('Y-m-d'));
                                    $later = new DateTime($order->renewal_date);
                                    $remaining = $later->diff($earlier)->format("%a"); ?>
                                    <span href="#" class="text-secondary">
                                <span class="label label-default">  <?=($order->type == 'domain' || $order->type == 'domain_only') ? $order->domain : $order->item_name; ?> </span> <span class="pull-right"><small><?=lang('hd_lang.next_payment')?>:</small> <span class="label <?=($remaining < 10) ? 'label-warning' : 'label-default'?>"> <?=$remaining?></span><small><?=strtolower(lang('hd_lang.days'))?></small></span> 
                                </span>
                                <?php  } ?>
                            </div>
                            

                        </section>

                    </div>

                </section>
        </div>

    </div>

    <div class="row p-3">
        
        <!-- Recent activities -->
        <div class="col-sm-6">
            <section class="box box-default">
                <div class="box-header common-h p-3"><?= lang('hd_lang.recent_activities') ?></div>
                <div class="box-body">
                    <section class="comment-list block">
                        <section class="slim-scroll" data-height="300" data-disable-fade-out="true" data-distance="0" data-size="5px" data-color="#333333" style="position: sticky; top:0; overflow: auto; width: auto; height: 300px; scrollbar-width: none; display: flex; flex-direction: column; gap: 5px; " >
                            <?php foreach (Client::recent_activities($user) as $key => $activity) { ?>
                                <article id="comment-id-1" class="comment-item small d-flex">
                                    <div class="pull-left thumb-sm">

                                        <img src="<?php echo User::avatar_url($activity->user); ?>" class="img-circle">

                                    </div>
                                    <section class="comment-body m-b-lg px-3 pull-left flex-grow-1">
                                        <header class="b-b common-p" style='border-bottom: 1px solid gray;'>
                                            <strong>
                                                <?php echo User::displayName($activity->user); ?></strong>
												<span class="text-muted text-xs common-p">
										<?php echo Applib::time_elapsed_string(strtotime($activity->activity_date));?>
												</span>
                                        </header>
                                        <div class='d-flex align-items-center justify-content-between'>
                                            <?php
                                            if (lang($activity->activity) != '') {
                                                if (!empty($activity->value1)) {
                                                    if (!empty($activity->value2)) {
                                                        echo sprintf(lang('hd_lang.'.$activity->activity), '<em class="common-p">' . $activity->value1 . '</em>', '<em class="common-p">' . $activity->value2 . '</em>');
                                                    } else {
                                                        echo sprintf('hd_lang.'.lang('hd_lang.'.$activity->activity), '<em class="common-p" >' . $activity->value1 . '</em>');
                                                    }
                                                } else {
                                                    echo lang('hd_lang.'.$activity->activity);
                                                }
                                            } else {
                                                echo $activity->activity;
                                            }
                                            ?>
                                        </div>
                                    </section>
                                </article>
                            <?php } ?>
                        </section>
                    </section>
                </div>
            </section>
        </div>

        <!-- Start Tickets -->
        <div class="col-sm-6">
            <section class="box box-info">
                <header class="box-header common-h p-3">
                        <?=lang('hd_lang.recent_tickets')?>
                </header>
                <div class="box-body">
                    <section class="slim-scroll" data-height="300" data-disable-fade-out="true" data-distance="0" data-size="5px" data-color="#333333" style="position: sticky; top:0; overflow: auto; width: auto; height: 300px; scrollbar-width: none; ">
                        <div class="list-group bg-white gap-2"  >
                                <?php
                                // $tickets = Ticket::by_where(array('reporter'=>$user)); // Get 7 tickets
                                $session = \Config\Services::session();                                 

                                // Connect to the database  
                                $db = \Config\Database::connect();

                                $tickets = $db->table('hd_tickets')->where('reporter', $user)->get()->getResult();

                                foreach ($tickets as $key => $ticket) {
                                    if($ticket->status == 'open'){ $badge = 'danger'; }elseif($ticket->status == 'closed'){ $badge = 'success'; }else{ $badge = 'dark'; }
                                    ?>
                                    <a href="<?=base_url()?>tickets/view/<?=$ticket->id?>" class='common-p p-2 my-1' style='border: 1px solid; border-radius: 10px;' data-original-title="<?=$ticket->subject?>"  title = "" class="list-group-item">
                                        <?=$ticket->ticket_code?> - <small class="text-muted"><?=lang('hd_lang.priority')?>: <?=$ticket->priority?> <span class="badge bg-<?=$badge?> pull-right"><?=$ticket->status?></span></small>
                                    </a>
                                <?php  } ?>
                        </div>
                    </section>

                </div>

            </section>
        </div>
        <!-- End Tickets -->
                
        <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
        
        <!-- pie chart js start -->
        <script>
            // Data
            var client_data = {
            labels: ['Paid', 'Outstanding'],
            datasets: [{
                data: [700, 0],
                backgroundColor: [
                'rgba(255, 159, 64, 0.8)', // Paid (orange)
                'rgba(255, 205, 86, 0.8)'   // Outstanding (yellow)
                ],
                borderWidth: 0
            }]
            };

            // Options
            var client_options = {
                responsive: false,
            cutoutPercentage: 70, // Inner radius of the donut chart
            plugins: {
                legend: {
                display: false // Hide legend
                },
                title: {
                    display: false // Hide title
                }
            }
            };

            // Get the context of the canvas element we want to select
            var client_ctx = document.getElementById('client_chart').getContext('2d');

            // Create the donut chart
            var myDonutChart = new Chart(client_ctx, {
            type: 'doughnut',
            data: client_data,
            options: client_options
            });
        </script>
        <!-- pie chart js end -->

    </div>

    <?= $this->endSection() ?>
</div>