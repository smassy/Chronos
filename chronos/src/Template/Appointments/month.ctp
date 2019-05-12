<?php
$this->loadHelper('Calendar.Calendar');

echo $this->Calendar->render();
?>
<script>
$(".cell-number").each(function () {
    $(this).click(function () {
        alert("You clicked day " + $(this).html());
    });
});
</script>
