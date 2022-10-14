<?php
if (!defined('_INCODE')) die('Access Denied...');
?>
<div class="debug-wrapper" style="width: 600px; padding: 20px 30px; text-align: center; margin: 0 auto;">
    <h2 style="text-transform: uppercase">Vui lòng kiểm tra và xử lý các lỗi sau</h2>
    <hr>
    <p>Code: <?php echo $debugError['error_code']; ?></p>
    <p><?php echo $debugError['error_message']; ?></p>
    <p>File: <?php echo $debugError['error_file']; ?></p>
    <p>Line: <?php echo $debugError['error_line']; ?></p>
</div>

