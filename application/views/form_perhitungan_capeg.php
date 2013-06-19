	<script type="text/javascript" src="<?php echo base_url() ?>js/niceforms.js"></script>
	<link rel="stylesheet" href="<?= base_url() ?>css/tab-view.css" type="text/css" media="screen">
	<script type="text/javascript" src="<?= base_url() ?>js/ajax.js"></script>
	<script type="text/javascript" src="<?= base_url() ?>js/tab-view.js"></script>
	<h2>Penghitungan Calon Pegawai</h2>
	<div>
	<?php echo validation_errors(); ?>
	<?php echo form_open(uri_string(), 'class ="niceform"');?>	
		<?php echo form_hidden('hitung',$hitung); ?>
				<a href="<?php echo base_url()?>index.php/pengelolaan_capeg">Kembali ke daftar calon pegawai</a>
                    
                    <table>
						<tr>
							<td><h4>Nama Calon Pegawai &nbsp;&nbsp; </h4></td>
							<td> : </td>
							<td><?php if($nama){ echo $nama; } else { echo '-'; } ?></td>
						</tr>
						<tr>
							<td><strong>Pilihan Bagian &nbsp;&nbsp; </strong></td>
							<td> : </td>
							<td><?php if($bagian_dipilih){ echo $bagian_dipilih; } else { echo '-'; } ?></td>
						</tr>	
						<tr>
							<td><strong>Nilai Pegawai &nbsp;&nbsp; </strong></td>
							<td> : </td>
							<td><?php if($nilai_pegawai){ echo $nilai_pegawai; } else { echo '-'; } ?></td>
						</tr>						
						<tr>
							<td>&nbsp; </td>
							<td>&nbsp; </td>
							<td>&nbsp;</td>
						</tr>
                    </table>
                    
					<div id="dhtmlgoodies_tabView1">
							
						<div class="dhtmlgoodies_aTab">						
							<table>
								<?php $no=1; ?>
								<?php foreach($kriteria->result() as $row){ ?>	
								<tr>
									<td><?php echo $no.'. '.$row->NAMA_KRITERIA.' '; ?> </td>
									<td>&nbsp;&nbsp;:&nbsp;&nbsp;</td>
									<td><?php 
									$subkriteria_dipilih = 0; if($subkriteria_pilihan[$row->KRITERIA_ID]!='') $subkriteria_dipilih = $subkriteria_pilihan[$row->KRITERIA_ID];
									echo form_dropdown($row->KRITERIA_ID, $subkriteria[$row->KRITERIA_ID], $subkriteria_dipilih, 'size="1"');
									?></td>
								</tr>
								<?php $no++; } ?>
								<tr>
									<td>&nbsp;</td>
									<td>&nbsp;</td>
									<td>&nbsp;</td>
								</tr>
								<tr>
									<td>&nbsp;</td>
									<td>&nbsp;</td>
									<td><input type="submit" name="submit" id="submit" value="Submit" /></td>
								</tr>														
							</table>
						</div>
		<?php echo form_close();?>
	</div>
<script type="text/javascript">
  initTabs('dhtmlgoodies_tabView1',Array('<?php echo $bagian_dipilih; ?>'),0,'100%','100%',Array(false));
</script>
