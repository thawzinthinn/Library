</div><!-- end content -->

<div class="footer">
    <div class="wrapper">

        <p>&copy;<?php echo date("Y"); ?> Personal Media Library</p>

    </div>
</div>


</body>
<script>
    setTimeout(() => {
        const flash = document.querySelector('.success-message');

        if (flash) {
            flash.style.display = 'none';
        }
    }, 2000);
</script>

</html>