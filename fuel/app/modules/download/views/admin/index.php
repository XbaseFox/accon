<?php echo render(FOUNDATION_VIEW_INDEX_HEADER); // 検索VIEWの標準ヘッダー ?>
<div class="row">
	<div class="col-md-12">
		<?php echo Form::open(array("id" => "search-condition-form", "class" => "form-inline")); ?>
		<?php echo Form::csrf(); // CSRF 保護 ?>
		<div id="search-condition" class="row search-condition-area">
			<div class="col-md-8 search-condition-input-box">
				<div class="col-md-12">
					<?php echo render(FOUNDATION_VIEW_SEARCH_DISPLAY_COUNTS); // 表示件数制御標準部品 ?>
				</div>
				<div class="col-md-4">
					<span class="input-group search-condition-input">
						<span class="input-group-addon">タイトル</span>
						<?php echo Form::input('title', isset($view_search['title']) ? $view_search['title'] : '', array('class' => 'form-control')); ?>
					</span>
				</div>
				<div class="col-md-4">
					<span class="input-group search-condition-input search-condition-input-end">
						<span class="input-group-addon">名前</span>
						<?php echo Form::input('name', isset($view_search['name']) ? $view_search['name'] : '', array('class' => 'form-control')); ?>
					</span>
				</div>
				<div class="col-md-4">
					<span class="input-group search-condition-input">
						<span class="input-group-addon">ステータス</span>
						<?php echo Form::select('status', isset($view_search['status']) ? $view_search['status'] : '', $status, array('class' => 'col-md-4 form-control')); ?>
					</span>
				</div>
			</div>
			<div class="col-md-4">
				<?php echo render(FOUNDATION_VIEW_SEARCH_ACTION); // 検索実行標準部品 ?>
			</div>
		</div>
		<?php echo Form::close(); ?>
	</div>
</div>
<div class="row">
	<div class="col-md-12">
		<?php if ($model): ?>
			<table class="table table-striped table-hover table-condensed" id="data_table">
				<thead>
				<tr>
					<th class="nowrap">タイトル&nbsp;<span class="badge">ID</span></th>
					<th class="nowrap">ステータス</th>
					<th class="nowrap">公開日時</th>
					<th class="nowrap">ファイル名</th>
					<th class="nowrap">説明</th>
					<th class="nowrap">表示回数</th>
					<th class="nowrap">取得回数</th>
					<th class="nowrap">更新者</th>
					<th class="nowrap">&nbsp;</th>
				</tr>
				</thead>
				<tbody>
				<?php foreach ($model as $item): ?>
					<tr id="search-result">
						<td class=""><?php echo mb_strimwidth($item->title, 0, 40, "...", "UTF-8" ).'&nbsp;<span class="badge">'.$item->id.'</span>'; ?></td>
						<td class="nowrap"><?php echo $status[$item->status]; ?></td>
						<td class=""><?php echo $item->public_at; ?></td>
						<td class=""><?php echo $item->name; ?></td>
						<td class=""><?php echo mb_strimwidth(strip_tags($item->description), 0, 100, "...", "UTF-8" ); ?></td>
						<td class="nowrap"><?php echo $item->impressions; ?></td>
						<td class="nowrap"><?php echo $item->get_number; ?></td>
						<td class="nowrap"><?php echo $item->user->username.'&nbsp;<span class="badge">'.$item->user_id.'</span>'; ?></td>
						<td class="nowrap"><?php echo render(FOUNDATION_VIEW_MODEL_ACTIONS, array(VIEW_ACTION_KEY => $item->id)); // 検索結果モデルアクション標準部品 ?></td>
					</tr>
				<?php endforeach; ?>
				</tbody>
			</table>
		<?php endif; ?>
	</div>
</div>
<?php echo render(FOUNDATION_VIEW_INDEX_FOOTER); // 検索VIEWの標準フッター ?>
<script>
	/**
	 * データテーブルの設定
	 */
	$('#data_table').dataTable({
		"order": [[2, 'desc']],
		"paging": false, // ページング停止
		"info": false,
		"bFilter": false, // 検索機能停止
		"aoColumnDefs": [{"bSortable": false, "aTargets": [8]}] // ソート除外
	});
</script>