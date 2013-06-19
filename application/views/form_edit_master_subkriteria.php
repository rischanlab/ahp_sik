	<script type="text/javascript" src="<?php echo base_url() ?>js/niceforms.js"></script>
	<script type="text/javascript">
		var base_url = "<?=base_url()?>";
		function submit_edit(){
			
			var kriteria = $("#kriteria").val();
			var subkriteria = $("#subkriteria").val();
			var bobot = $("#bobot").val();
			var data_url = base_url+"master_subkriteria/edit_proses/<?=$subkriteria_id?>/<?=$kriteria_id?>";
			var data_post = 'kriteria='+kriteria+'&subkriteria='+subkriteria+'&bobot='+bobot;
			var div_loading = 'submit_loading';
			var div_result = 'edit_subkriteria_detail';
			post_html_data(data_url,data_post,div_loading,div_result,'append');
		}
	</script>
	<h2>Edit Subkriteria</h2>
	<div id="edit_subkriteria_detail">
	<?php echo validation_errors(); ?>
	<?php echo form_open('master_subkriteria/edit_proses/'.$subkriteria_id.'/'.$kriteria_id, 'class ="niceform"');?>						
				<a href="<?php echo base_url()?>index.php/master_subkriteria/grid/<?php echo $kriteria_id;?>">Kembali ke master subkriteria</a>
                    <dl>
                        <dt><label for="kriteria">Kriteria : </label></dt>
                        <dd>
                        <?php
							if(set_value('kriteria')!=0) $kriteria_dipilih = set_value('kriteria');
							echo form_dropdown('kriteria', $kriteria, $kriteria_dipilih, 'size="1" id="kriteria"');
						?>
						</dd>
                    </dl>
                    <dl>
                        <dt><label for="subkriteria">Nama Subkriteria : </label></dt>
                        <?php if(set_value('subkriteria')!='') $subkriteria = set_value('subkriteria')?>
						<dd><?php echo form_input(array('name'=>'subkriteria', 'id'=>'subkriteria' ,'size'=>'20','type'=>'text', 'maxlength'=>'255', 'value'=>$subkriteria)); ?></dd>
                    </dl>
                    
                    <dl class="submit">
						<dd><input type="submit" name="submit" id="submit" value="Submit" onclick="submit_edit();return false;"/></dd>
						<span id="submit_loading"></span>
                    </dl>
		<?php echo form_close();?>
	</div>
