                                <?php if($v['voucher_type'] == "order"): ?>
                                    <div class="voucher order" value="<?=$v['id']?>"
                                                        data-condition="<?=$v['voucher_condition']?>"
                                                        data-max="<?=$v['voucher_max']?>"
                                                        data-id="<?=$v['id']?>"
                                                        data-discount="<?=$v['voucher_discount']?>"
                                                        data-ship="0">Sale <?=$v['voucher_discount']?>% | Max <?=$v['voucher_max']?>$
                                    </div>
                                <?php elseif($v['voucher_type'] == "shipping"): ?>
                                    <?php if($v['voucher_discount'] == 25): ?>
                                        <div class="voucher free" value="<?=$v['id']?>"
                                                        data-condition="<?=$v['voucher_condition']?>"
                                                        data-max="<?=$v['voucher_max']?>"
                                                        data-id="<?=$v['id']?>"
                                                        data-discount="<?=$v['voucher_discount']?>"
                                                        data-ship="1"><svg class="shipping icon" viewBox="0 0 640 512" aria-hidden="true">
                                                                        <path d="M64 96c0-35.3 28.7-64 64-64h288c35.3 0 64 28.7 64 64v32h50.7c17 0 33.3 6.7 45.3 18.7L621.3 192c12 12 18.7 28.3 18.7 45.3V384c0 35.3-28.7 64-64 64h-3.3c-10.4 36.9-44.4 64-84.7 64s-74.2-27.1-84.7-64H300.7c-10.4 36.9-44.4 64-84.7 64s-74.2-27.1-84.7-64H128c-35.3 0-64-28.7-64-64v-48H24c-13.3 0-24-10.7-24-24s10.7-24 24-24h112c13.3 0 24-10.7 24-24s-10.7-24-24-24H24c-13.3 0-24-10.7-24-24s10.7-24 24-24h176c13.3 0 24-10.7 24-24s-10.7-24-24-24H24c-13.3 0-24-10.7-24-24S10.7 96 24 96h40zm512 192v-50.7l-45.3-45.3H480v96h96zM256 424a40 40 0 1 0-80 0 40 40 0 1 0 80 0zm232 40a40 40 0 1 0 0-80 40 40 0 1 0 0 80z"/>
                                                                      </svg>
                                                                      Free Ship
                                        </div>
                                    <?php else: ?>
                                        <div class="voucher ship" value="<?=$v['id']?>"
                                                        data-condition="<?=$v['voucher_condition']?>"
                                                        data-max="<?=$v['voucher_max']?>"
                                                        data-id="<?=$v['id']?>"
                                                        data-discount="<?=$v['voucher_discount']?>"
                                                        data-ship="1">$<?=$v['voucher_discount']?> Ship OFF
                                        </div>
                                    <?php endif; ?>
                                <?php endif; ?>