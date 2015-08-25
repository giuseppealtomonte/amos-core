<div class="fileinput fileinput-new" data-provides="fileinput">
	<div class="fileinput-new thumbnail" style="width: 100px; height: 100px;">
		<?=$thumbnail;?>
	</div>
	<div class="fileinput-preview fileinput-exists thumbnail" style="max-width: 200px; max-height: 100px;"></div>
	<div>
		<span class="btn btn-default btn-file btn-block">
                    <span class="fileinput-new"><span id="bk-btnImport" class="ti ti-upload"></span></span>
                    <span class="fileinput-exists"><span id="bk-btnImport" class="ti ti-upload"></span></span>
			<?=$field;?>
		</span>
            <a href="#" class="btn btn-default btn-block fileinput-exists" data-dismiss="fileinput"><span class="ti ti-trash"></span></a>
	</div>
</div>
