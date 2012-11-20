	<script type="text/javascript" src="<?php echo base_url() ?>js/niceforms.js"></script>
	<h2>Edit Subkriteria</h2>
	<div>
	<?php echo validation_errors(); ?>
	<?php echo form_open(uri_string(), 'class ="niceform"');?>	
				<?php $kodeuser = $this->session->userdata('kode_role');	
				echo form_hidden('role', $role_dipilih);
				if($kodeuser == "1"){ ?>
				<a href="<?php echo base_url()?>index.php/master_pengguna/grid/">Kembali ke master pengguna</a>
				<?php } else { ?>
				<a href="<?php echo base_url()?>index.php/user_menu/grid/">Kembali ke data user</a>
                <?php } ?>
					<dl>
                        <dt><label for="nama">Nama Pengguna : </label></dt>
                        <?php if(set_value('nama')!='') $nama = set_value('nama')?>
						<dd><?php echo form_input(array('name'=>'nama', 'id'=>'nama' ,'size'=>'54','type'=>'text', 'maxlength'=>'255', 'value'=>$nama)); ?></dd>
                    </dl>
					<dl>
                        <dt><label for="role">Role Pengguna : </label></dt>
                        <dt>
                        <?php
							echo $nama_role;							
						?>
						</dt>
                    </dl>
                    <dl>
                        <dt><label for="username">Username : </label></dt>
                        <?php if(set_value('username')!='') $username = set_value('username')?>
						<dd><?php echo form_input(array('name'=>'username', 'id'=>'username' ,'size'=>'54','type'=>'text', 'maxlength'=>'255', 'value'=>$username)); ?></dd>
                    </dl>
                    <dl>
                        <dt><label for="password">Password : </label></dt>
                        <?php $password = ''; if(set_value('password')!='') $password = set_value('password')?>
						<dd><?php echo form_input(array('name'=>'password', 'id'=>'password' ,'size'=>'54','type'=>'password', 'maxlength'=>'255', 'value'=>$password)); ?></dd>
                    </dl>
					<dl>
                        <dt><label for="konf_password">Konfirmasi Password : </label></dt>
                        <?php $konf_password = ''; if(set_value('konf_password')!='') $password = set_value('konf_password')?>
						<dd><?php echo form_input(array('name'=>'konf_password', 'id'=>'konf_password' ,'size'=>'54','type'=>'password', 'maxlength'=>'255', 'value'=>$konf_password)); ?></dd>
                    </dl>
                    <dl class="submit">
						<dd><input type="submit" name="submit" id="submit" value="Submit" /></dd>
                    </dl>
		<?php echo form_close();?>
	</div>
