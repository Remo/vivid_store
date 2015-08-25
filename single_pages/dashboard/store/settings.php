<?php defined('C5_EXECUTE') or die("Access Denied."); ?>
	    
	    <div class="ccm-dashboard-header-buttons">
            <a href="<?php echo View::url('/dashboard/store/settings/shipping')?>" class="btn btn-primary"><i class="fa fa-gift"></i> <?php echo t("Shipping Methods")?></a>
            <a href="<?php echo View::url('/dashboard/store/settings/payment')?>" class="btn btn-primary"><i class="fa fa-money"></i> <?php echo t("Payment Methods")?></a>
        </div>
	    
	    <form method="post" action="<?=$view->action('save')?>">
	        
            <div class="row">
                
                <div class="col-sm-4">
                    
                    <div class="vivid-store-side-panel">
                    
                        <ul>
                            <li><a href="#settings-currency" data-pane-toggle class="active"><?=t('Currency')?></a></li>
                            <li><a href="#settings-tax" data-pane-toggle><?=t('Tax')?></a></li>
                            <li><a href="#settings-shipping" data-pane-toggle><?=t('Shipping')?></a></li>
                            <li><a href="#settings-payments" data-pane-toggle><?=t('Payments')?></a></li>
                            <li><a href="#settings-order-statuses" data-pane-toggle><?=t('Order Statuses')?></a></li>
                            <li><a href="#settings-notifications" data-pane-toggle><?=t('Notifications')?></a></li>
                            <li><a href="#settings-products" data-pane-toggle><?=t('Products')?></a></li>
                            <li><a href="#settings-checkout" data-pane-toggle><?=t('Cart and Checkout')?></a></li>
                        </ul>
                    
                    </div>
                    
                </div>
                
                <div class="col-sm-7 store-pane active" id="settings-currency">
                    
                    <div class="form-group">
                        <?php echo $form->label('symbol',t('Currency Symbol')); ?>
                        <?php echo $form->text('symbol',Config::get('vividstore.symbol'),array("style"=>"width:60px;"));?>
                    </div>
                    <div class="form-group">
                        <?php echo $form->label('thousand',t('Thousands Separator %se.g. , or a space%s', "<small>", "</small>")); ?>
                        <?php echo $form->text('thousand',Config::get('vividstore.thousand'),array("style"=>"width:60px;"));?>
                    </div>
                    <div class="form-group">
                        <?php echo $form->label('whole',t('Whole Number Separator %se.g. period or a comma%s', "<small>", "</small>")); ?>
                        <?php echo $form->text('whole',Config::get('vividstore.whole'),array("style"=>"width:60px;")); ?>
                    </div>
            
                </div><!-- #settings-currency -->
                
                <div class="col-sm-7 store-pane" id="settings-tax" data-states-utility="<?=View::url('/checkout/getstates')?>">
                
                    <h3><?=t("Tax Configuration")?></h3>

                    <div class="row">
                        <div class="col-xs-4">
                            <div class="form-group">
                                <?php echo $form->label('taxEnabled',t('Enable Tax')); ?>
                                <?php echo $form->select('taxEnabled',array('no'=>t('No'),'yes'=>t('Yes')),Config::get('vividstore.taxenabled')); ?>
                            </div>
                        </div>
                        <div class="col-xs-4">
                            <div class="form-group">
                                <label for="taxName"><?=t("Tax Label")?></label>
                                <?php echo $form->text('taxName',Config::get('vividstore.taxName'));?>
                            </div>
                        </div>
                        <div class="col-xs-4">
                            <div class="form-group">
                                <?php echo $form->label('taxRate',t('Tax Rate %')); ?>
                                <div class="input-group">
                                    <?php echo $form->text('taxRate',Config::get('vividstore.taxrate')); ?>
                                    <div class="input-group-addon">%</div>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <div class="form-group">
                        <label for="calculation"><?=t("Are Prices Entered with Tax Included?")?></label>
                        <?php echo $form->select('calculation',array('add'=>t("No, I will enter product prices EXCLUSIVE of tax"),'extract'=>t("Yes, I will enter product prices INCLUSIVE of tax")),Config::get('vividstore.calculation')); ?>
                    </div>

                    <div class="form-group">
                        <label for="taxBased"><?=t("Tax is Based on the")?></label>
                        <?php echo $form->select('taxBased',array('subtotal'=>t("Product Total"),'grandtotal'=>t("Product Total + Shipping")),Config::get('vividstore.taxBased')); ?>
                    </div>
                    
                    <h3><?=t("When to Charge Tax")?></h3>
                    
                    <?php echo $form->select('calculation',array('add'=>t("Calculated from total and added to order"),'extract'=>t("Already in product prices, only display as component of total")),Config::get('vividstore.calculation')); ?>
                    
                    <div class="row">
                        
                        <div class="col-sm-5">
                   
                            <div class="form-group">
                                <label for="taxAddress" class="control-label"><?=t("If the Customers...")?></label>
                            <?php echo $form->select('taxAddress',array('shipping'=>t("Shipping Address"),'billing'=>t("Billing Address")),Config::get('vividstore.taxAddress')); ?>
                            </div>
                        
                        </div>
                        
                        <div class="col-sm-7">
                        
                            <p><strong><?=t("Matches...")?></strong></p>
                            <div class="form-horizontal">
                            <div class="form-group">
                                <label for="taxCountry" class="col-sm-5 control-label"><?=t("Country")?> <small class="text-muted"><?=t("Required")?></small></label>
                                <div class="col-sm-7">    
                                    <?php $country = Config::get('vividstore.taxcountry'); ?>
                                    <?php echo $form->select('taxCountry',$countries,$country?$country:'US',array("onchange"=>"updateTaxStates()")); ?>    
                                </div>
                            </div>
                            
                            
                            <div class="form-group">
                                <label for="taxState" class="col-sm-5 control-label"><?=t("Region")?> <small class="text-muted"><?=t("Optional")?></small></label>
                                <div class="col-sm-7"> 
                                    <?php $state = Config::get('vividstore.taxstate'); ?>
                                    <?php echo $form->select('taxState',$states,$state?$state:"", array('disabled'=>'disabled','class'=>"form-control")); ?>
                                    <?php echo $form->hidden("savedTaxState",$state); ?>
                                </div>
                            </div>
        
                            <div class="form-group">
                                <label for="taxState" class="col-sm-5 control-label"><?=t("City")?> <small class="text-muted"><?=t("Optional")?></small></label>
                                <div class="col-sm-7"> 
                                    <?php echo $form->text('taxCity',Config::get('vividstore.taxcity'));?>
                                </div>
                            </div>
                            </div>
                        </div>

                    </div>

                </div>
                                
                <div class="col-sm-7 store-pane" id="settings-shipping">
                
                    <h3><?=t("Terminology")?></h3>
                    <div class="row">
                        <div class="col-xs-6">
                            <div class="form-group">
                                <?php echo $form->label('weightUnit',t('Units for Weight'));?>
                                <?php echo $form->select('weightUnit',array('lb'=>t('lb'),'kg'=>t('kg')),Config::get('vividstore.weightUnit'));?>
                            </div>
                        </div> 
                        <div class="col-xs-6">
                            <div class="form-group">
                                <?php echo $form->label('sizeUnit',t('Units for Size'));?>
                                <?php echo $form->select('sizeUnit',array('in'=>t('in'),'cm'=>t('cm')),Config::get('vividstore.sizeUnit'));?>
                            </div>
                        </div>                        
                    </div>
                    
            
                </div><!-- #settings-shipping -->
                    
                <div class="col-sm-7 store-pane" id="settings-payments">
                    
                    <?php
                        if($installedPaymentMethods){
                            foreach($installedPaymentMethods as $pm){?>
                            
                            <div class="panel panel-default">
                            
                                <div class="panel-heading"><?=$pm->getPaymentMethodName()?></div>
                                <div class="panel-body">
                                    <div class="form-group paymentMethodEnabled">
                                        <input type="hidden" name="paymentMethodHandle[<?=$pm->getPaymentMethodID()?>]" value="<?=$pm->getPaymentMethodHandle()?>">
                                        <label><?=t("Enabled")?></label>
                                        <?php
                                            echo $form->select("paymentMethodEnabled[".$pm->getPaymentMethodID()."]", array(0=>"No",1=>"Yes"),$pm->isEnabled());
                                        ?>
                                    </div>
                                    <div id="paymentMethodForm-<?php echo $pm->getPaymentMethodID(); ?>" style="display:<?php echo $pm->isEnabled() ? 'block':'none'; ?>">
                                        <div class="form-group">
                                            <label><?=t("Display Name (on checkout)")?></label>
                                            <?php echo $form->text('paymentMethodDisplayName['.$pm->getPaymentMethodID().']',$pm->getPaymentMethodDisplayName()); ?>
                                        </div>
                                        <?php
                                            $pm->renderDashboardForm();
                                        ?>
                                    </div>
                                </div>
                            
                            </div>
                            
                        <?php        
                            }
                        } else {
                            echo t("No Payment Methods are Installed");
                        }
                    ?>                                    
                    
                    <script>
                        $(function(){
                            $('.paymentMethodEnabled SELECT').on('change',function(){
                                $this = $(this);
                                if ($this.val()==1) {
                                    $this.parent().next().slideDown();
                                } else {
                                    $this.parent().next().slideUp();
                                }
                            });
                        });
                    </script>
                </div><!-- #settings-payments -->

                <div class="col-sm-7 store-pane" id="settings-order-statuses">

                    <?php
                    if(count($orderStatuses)>0){ ?>
                        <div class="panel panel-default">

                            <table class="table" id="orderStatusTable">
                                <thead>
                                <tr>
                                    <th rowspan="1">&nbsp;</th>
                                    <th rowspan="1"><?php echo t('Display Name'); ?></th>
                                    <th rowspan="1"><?php echo t('Default Status'); ?></th>
                                    <th colspan="2" style="display:none;"><?php echo t('Send Change Notifications to...'); ?></th>
                                </tr>
                                <tr style="display:none;">
                                    <th><?php echo t('Site'); ?></th>
                                    <th><?php echo t('Customer'); ?></th>
                                </tr>
                                </thead>
                                <tbody>
                                <?php foreach($orderStatuses as $orderStatus){?>
                                    <tr>
                                        <td class="sorthandle"><input type="hidden" name="osID[]" value="<?php echo $orderStatus->getID(); ?>"><i class="fa fa-arrows-v"></i></td>
                                        <td><input type="text" name="osName[]" value="<?php echo $orderStatus->getName(); ?>" placeholder="<?php echo $orderStatus->getReadableHandle(); ?>" class="form-control ccm-input-text"></td>
                                        <td><input type="radio" name="osIsStartingStatus" value="<?php echo $orderStatus->getID(); ?>" <?php echo $orderStatus->isStartingStatus() ? 'checked':''; ?>></td>
                                        <td style="display:none;"><input type="checkbox" name="osInformSite[]" value="1" <?php echo $orderStatus->getInformSite() ? 'checked':''; ?> class="form-control"></td>
                                        <td style="display:none;"><input type="checkbox" name="osInformCustomer[]" value="1" <?php echo $orderStatus->getInformCustomer() ? 'checked':''; ?> class="form-control"></td>
                                    </tr>
                                <?php } ?>
                                </tbody>
                            </table>
                            <script>
                                $(function(){
                                    $('#orderStatusTable TBODY').sortable({
                                        cursor: 'move',
                                        opacity: 0.5,
                                        handle: '.sorthandle'
                                    });

                                });

                            </script>

                        </div>

                    <?php
                    } else {
                        echo t("No Order Statuses are available");
                    }
                    ?>


                </div><!-- #settings-order-statuses -->

                <div class="col-sm-7 store-pane" id="settings-notifications">
                
                    <div class="form-group">
                        <?php echo $form->label('notificationEmails',t('Enter Emails to Notify of New Orders %sseparate multiple emails with commas%s', '<small class="text-muted">','</small>')); ?>
                        <?php echo $form->text('notificationEmails',Config::get('vividstore.notificationemails'));?>
                    </div>
                    
                    <div class="form-group">
                        <?php echo $form->label('emailAlert',t('Email address to send alerts from'));?>
                        <?php echo $form->text('emailAlert',Config::get('vividstore.emailalerts')); ?>
                    </div>
            
                </div>

                <!-- #settings-products -->
                <div class="col-sm-7 store-pane" id="settings-products">
                
                    <div class="form-group">
                        <?php echo $form->label('productPublishTarget',t('Page to Publish Product Pages Under'));?>
                        <?=$pageSelector->selectPage('productPublishTarget',$productPublishTarget)?>
                    </div>
            
                </div>

                <!-- #settings-customers -->
                <div class="col-sm-7 store-pane" id="settings-checkout">

                    <h3><?=t("Shopping Cart")?></h3>
                    <div class="form-group">
                        <?php echo $form->label('cartOverlay',t('When Clicking on "View Cart" in Utility Links:')); ?>
                        <?php echo $form->select('cartOverlay',array(false=>"Go to Cart Page",true=>"Display Cart Overlay"), Config::get('vividstore.cartOverlay')); ?>
                    </div>

                    <h3><?php echo t('Guest checkout');?></h3>
                    <div class="form-group">
                        <?php $guestCheckout =  Config::get('vividstore.guestCheckout');
                        $guestCheckout = ($guestCheckout ? $guestCheckout : 'off');
                        ?>
                        <label><?php echo $form->radio('guestCheckout','off', $guestCheckout == 'off' || $guestCheckout == '' ); ?> <?php  echo t('Disabled'); ?></label><br />
                        <label><?php echo $form->radio('guestCheckout','option',$guestCheckout == 'option'); ?> <?php  echo t('Offer as checkout option'); ?></label><br />
                        <label><?php echo $form->radio('guestCheckout','always', $guestCheckout == 'always'); ?> <?php  echo t('Always (unless login required for products in cart)'); ?></label><br />

                    </div>

                </div>

            </div><!-- .row -->
                
    	    <div class="ccm-dashboard-form-actions-wrapper">
    	        <div class="ccm-dashboard-form-actions">
    	            <button class="pull-right btn btn-success" type="submit" ><?=t('Save Settings')?></button>
    	        </div>
    	    </div>

	    </form>