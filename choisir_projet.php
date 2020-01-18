<?php
require_once('_header.php');
?>
<form action="editFM_part1.php" method="post" role="form">
    <div class="form-row ">
        <div class="col-2" >
            <label for="projet">Choisissez le projet</label>
            <select class="custom-select form-control" id="projet" name="projet">
                <?php options('projet','projet');?>
            </select>
        </div>
    </div>
        <button type="submit" class="btn btn-success btn-lg" >Next</button>
</form>
<?php
require_once ('_footer.php');
?>
