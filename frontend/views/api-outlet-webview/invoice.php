<?php
use common\helpers\Strings;
?>

<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/4.6.1/css/bootstrap.min.css" integrity="sha512-T584yQ/tdRR5QwOpfvDfVQUidzfgc2339Lc8uBDtcp/wYu80d7jwBgAxbyMh0a9YM9F8N3tdErpFI8iaGx6x5g==" crossorigin="anonymous" referrerpolicy="no-referrer" />
<link rel="stylesheet" type="text/css" href="<?= Yii::getAlias('@fronturl').'/css/invoice.css' ?>">

<div style="margin-top: 20px; margin-left: 20px; margin-right: 20px;">
	<table width="100%">
		<tr>
			<td width="100px">
				<img src="<?= Yii::$app->request->baseUrl ?>/images/pus-logo.png">
			</td>
			<td width="10px"></td>
			<td>
				<h3 style="margin-bottom: 0px;">INVOICE (<?= $order->invoice_number ?>)</h3>
				<table>
					<tr>
						<td>Outlet Name</td>
						<td>:</td>
						<td><?= $order->outlet->name ?></td>
					</tr>
					<tr>
						<td>Ordered At</td>
						<td>:</td>
						<td><?= date('d M Y', strtotime($order->created_at)) ?></td>
					</tr>
				</table>
			</td>
			<td>
				<?php if ($order->status == 'PAID'): ?>
					<div style="border: 5px solid green; padding: 5px; text-align: center; font-size: 30px; font-weight: 800; color: grey;">
						PAID
					</div>
				<?php else: ?>
					<div style="border: 5px solid red; padding: 5px; text-align: center; font-size: 30px; font-weight: 800; color: grey;">
						UNPAID
					</div>
				<?php endif; ?>
			</td>
		</tr>
	</table>
	<br>
	<table class="table table-bordered">
		<tr>
			<th>NO</th>
			<th>PRODUCT NAME</th>
			<th>QTY</th>
			<th>PRICE</th>
			<th>SUBTOTAL</th>
		</tr>
		<?php
		$i = 1;
		$total = 0;
		foreach($order->orderDetails as $detail):
			$subtotal = $detail->quantity*$detail->price_sale;
			$total += $subtotal;
			?>
			<tr>
				<td><?= $i++ ?></td>
				<td><?= $detail->product->name ?></td>
				<td><?= $detail->quantity ?></td>
				<td class="right"><?= Strings::getRupiah($detail->price_sale) ?></td>
				<td class="right"><?= Strings::getRupiah($subtotal) ?></td>
			</tr>
		<?php endforeach; ?>
		<tr>
			<th class="right no-border" colspan="4">Product Total</th>
			<th class="right"><?= Strings::getRupiah($total) ?></th>
		</tr>
		<tr>
			<th class="right no-border" colspan="4">Delivery Cost</th>
			<th class="right"><?= Strings::getRupiah($order->shipping_cost) ?></th>
		</tr>
		<tr>
			<th class="right no-border" colspan="4">Tax</th>
			<th class="right"><?= Strings::getRupiah($order->tax) ?></th>
		</tr>
		<tr>
			<th class="right no-border" colspan="4">Total</th>
			<th class="right"><?= Strings::getRupiah($total+$order->shipping_cost+$order->tax) ?></th>
		</tr>
	</table>
	<strong>Pembayaran:</strong><br>
	Lakukan pembayaran sesuai dengan total biaya di atas. Pembayaran bisa dilakukan melalui transfer ke rekening di bawah ini:<br>
	<table>
		<tr>
			<td valign="top"><img src="<?= Yii::$app->request->baseUrl ?>/images/bank-mandiri.png" width="70px"><br></td>
			<td style="padding-left: 10px">
				Bank = Mandiri<br>
				Nomor Rekening = 32864837248<br>
				Atas Nama = PT. Pangan Unggul Sentosa
			</td>
		</tr>
	</table>
	<br><br>
	<strong>Catatan:</strong><br>
	Pembayaran maksimal adalah 7 hari setelah invoice ini dibuat, yaitu pada tanggal <?= date('d M Y', strtotime('+7 days', strtotime($order->ordered_at))) ?>. Terima kasih
</div>