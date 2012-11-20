	<script type="text/javascript" src="<?php echo base_url() ?>js/niceforms.js"></script>
	<script type="text/javascript">
		var base_url = "<?=base_url()?>";
		function submit_edit(){
			var kriteria = $("#kriteria").val();
			var data_url = base_url+"master_kriteria/edit_proses/<?=$kriteria_id?>";
			var data_post = 'kriteria='+kriteria;
			var div_loading = 'submit_loading';
			var div_result = 'edit_kriteria_detail';
			post_html_data(data_url,data_post,div_loading,div_result,'append');
		}
	</script>
	<h2>Edit Kriteria</h2>
	<div id="edit_kriteria_detail">
	<?php echo validation_errors(); ?>
	<?php echo form_open('master_kriteria/edit_proses'.$kriteria_id, 'class ="niceform" name="form_edit_master_kriteria"');?>						
				<a href="<?php echo base_url()?>index.php/master_kriteria">Kembali ke master kriteria</a>
                    <dl>
                        <dt><label for="jabatan">Nama Kriteria : </label></dt>
                        <?php if(set_value('kriteria')!='') $kriteria = set_value('kriteria')?>
						<dd><?php echo form_input(array('name'=>'kriteria', 'id'=>'kriteria' ,'size'=>'54','type'=>'text', 'maxlength'=>'255', 'value'=>$kriteria)); ?></dd>
                    </dl>
                    <dl class="submit">
						<dd><input type="submit" name="submit" id="submit" value="Submit" onclick="submit_edit();return false;"/></dd>
						<span id="submit_loading"></span>
                    </dl>
		<?php echo form_close();?>
	</div>
