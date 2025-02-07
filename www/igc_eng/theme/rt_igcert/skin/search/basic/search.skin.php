<?php
if (!defined("_GNUBOARD_")) exit; // 개별 페이지 접근 불가

// add_stylesheet('css 구문', 출력순서); 숫자가 작을 수록 먼저 출력됨
add_stylesheet('<link rel="stylesheet" href="'.$search_skin_url.'/style.css">', 0);
?>

<!-- 전체검색 시작 { -->
<form name="fsearch" onsubmit="return fsearch_submit(this);" method="get">
<input type="hidden" name="srows" value="<?php echo $srows ?>">
<fieldset id="sch_res_detail">
    <legend>Advanced Search </legend>
    <?php echo $group_select ?>
    <script>document.getElementById("gr_id").value = "<?php echo $gr_id ?>";</script>

    <label for="sfl" class="sound_only">search requirement</label>
    <select name="sfl" id="sfl">
        <option value="wr_subject||wr_content"<?php echo get_selected($_GET['sfl'], "wr_subject||wr_content") ?>>Title+Content1</option>
        <option value="wr_subject"<?php echo get_selected($_GET['sfl'], "wr_subject") ?>>Title</option>
        <option value="wr_content"<?php echo get_selected($_GET['sfl'], "wr_content") ?>>Contents</option>
        <option value="mb_id"<?php echo get_selected($_GET['sfl'], "mb_id") ?>>Member ID</option>
        <option value="wr_name"<?php echo get_selected($_GET['sfl'], "wr_name") ?>>Name</option>
    </select>

    <label for="stx" class="sound_only">Key Word<strong class="sound_only"> Necessary</strong></label>
    <span class="sch_wr">
        <input type="text" name="stx" value="<?php echo $text_stx ?>" id="stx" required class="frm_input" size="40">
        <button type="submit" class="btn_submit"><i class="fa fa-search" aria-hidden="true"></i> Search</button>
    </span>

    <script>
    function fsearch_submit(f)
    {
        if (f.stx.value.length < 2) {
            alert("Please enter at least two characters for your search term.");
            f.stx.select();
            f.stx.focus();
            return false;
        }

        // 검색에 많은 부하가 걸리는 경우 이 주석을 제거하세요.
        var cnt = 0;
        for (var i=0; i<f.stx.value.length; i++) {
            if (f.stx.value.charAt(i) == ' ')
                cnt++;
        }

        if (cnt > 1) {
            alert("You can enter only one space in the search term for quick search..");
            f.stx.select();
            f.stx.focus();
            return false;
        }

        f.action = "";
        return true;
    }
    </script>

	<div class="switch_field">
		<input type="radio" value="and" <?php echo ($sop == "and") ? "checked" : ""; ?> id="sop_and" name="sop">
    	<label for="sop_and">AND</label>
		<input type="radio" value="or" <?php echo ($sop == "or") ? "checked" : ""; ?> id="sop_or" name="sop" >
		<label for="sop_or">OR</label>
	</div>
</fieldset>
</form>

<div id="sch_result">
   
   
   <!-----?php //  내용관리 검색 기능 시작
     if (number_format($page) == 1) { // 첫번째 검색 페이지에서만 노출
     echo '<section class="sch_res_list">';
     $sql = "SELECT * FROM g5_content WHERE (co_content LIKE '%$stx%' OR co_subject LIKE '%$stx%')";
     $re = sql_query($sql);
     $i = 0;
         while ($result = sql_fetch_array($re)) {
             $co_href = G5_BBS_URL.'/content.php?co_id='.$result['co_id'];
             print '<h2><a href="'.$co_href.'">'.$result['co_subject'].' </a>페이지내 결과</h2>';
             print '<ul><li><a href="'.$co_href.'">'.$result['co_subject'].'</a><p>';
             print cut_str(strip_tags($result['co_content']),200).'</p></li></ul>';
             $i++;
         }
     if($i == 0){
         echo '<div class="empty_list">페이지에서는 검색된 자료가 하나도 없습니다.</div>';
         } else {
             echo '페이지에서 '.$i.'개의 결과가 검색되었습니다.';
         }
     
     echo  '</section>';
     }  //  내용관리 검색 기능 끝
     ?--------->
   
    <?php
    if ($stx) {
        if ($board_count) {
    ?>
    <section id="sch_res_ov">
        <h2><strong><?php echo $stx ?> &nbsp; </strong> Search Results in the board</h2>
        <ul>
            <li>Notice board <?php echo $board_count ?>PCS</li>
            <li>Post <?php echo number_format($total_count) ?>PCS</li>
        	<li><?php echo number_format($page) ?>/<?php echo number_format($total_page) ?> Reading page</li>
        </ul>
    </section>
    <?php
        }
    }
    ?>

    <?php
    if ($stx) {
        if ($board_count) {
     ?>
    <ul id="sch_res_board">
        <li><a href="?<?php echo $search_query ?>&amp;gr_id=<?php echo $gr_id ?>" <?php echo $sch_all ?>>All Bulletin Board</a></li>
        <?php echo $str_board_list; ?>
    </ul>
    <?php
        } else {
     ?>
    <div class="empty_list">No data was found.</div>
    <?php } }  ?>

    <hr>

    <?php if ($stx && $board_count) { ?><section class="sch_res_list"><?php }  ?>
    <?php
    $k=0;
    for ($idx=$table_index, $k=0; $idx<count($search_table) && $k<$rows; $idx++) {
     ?>
        <h2><a href="<?php echo get_pretty_url($search_table[$idx], '', $search_query); ?>"><?php echo $bo_subject[$idx] ?> Results in the bulletin board</a></h2>
        <ul>
        <?php
        for ($i=0; $i<count($list[$idx]) && $k<$rows; $i++, $k++) {
            if ($list[$idx][$i]['wr_is_comment'])
            {
                $comment_def = '<span class="cmt_def"><i class="fa fa-commenting-o" aria-hidden="true"></i><span class="sound_only">댓글</span></span> ';
                $comment_href = '#c_'.$list[$idx][$i]['wr_id'];
            }
            else
            {
                $comment_def = '';
                $comment_href = '';
            }
         ?>

            <li>
                <div class="sch_tit">
                    <a href="<?php echo $list[$idx][$i]['href'] ?><?php echo $comment_href ?>" class="sch_res_title"><?php echo $comment_def ?><?php echo $list[$idx][$i]['subject'] ?></a>
                    <a href="<?php echo $list[$idx][$i]['href'] ?><?php echo $comment_href ?>" target="_blank" class="pop_a"><i class="fa fa-window-restore" aria-hidden="true"></i><span class="sound_only">새창</span></a>
                </div>
                <p><?php echo $list[$idx][$i]['content'] ?></p>
                <div class="sch_info">
                    <?php echo $list[$idx][$i]['name'] ?>
                    <span class="sch_datetime"><i class="fa fa-clock-o" aria-hidden="true"></i> <?php echo $list[$idx][$i]['wr_datetime'] ?></span>
                </div>
            </li>
        <?php }  ?>
        </ul>
        <a href="<?php echo get_pretty_url($search_table[$idx], '', $search_query); ?>" class="sch_more">더보기</a>
    <?php }  ?>
    <?php if ($stx && $board_count) {  ?></section><?php }  ?>

    <?php echo $write_pages ?>

</div>
<!-- } 전체검색 끝 -->