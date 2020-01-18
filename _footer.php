<!-- Footer -->
<footer class="page-footer font-small blue pt-4">
   <br>
    <div class="footer-copyright text-center py-3">© <?php echo(date('Y')); ?> Copyright
        <a href="https://www.linkedin.com/in/hamouda-fares-172074155/   ">Fares Hamouda </a>
    </div>

</footer>
<!-- Footer -->
<script>
    function myFunction() {
        totals = document.getElementsByClassName('total');
        var total_espece=document.getElementById('total_espece');
        total_espece.value=0;
        for (var total of totals) {
            var length_total=total.id.length;
            var id_total= total.id.substring(length_total-1,length_total);
            var tableau_payment= payment(id_total);
            total.value=0;
            for (var element of tableau_payment) {
                var type = element.options[element.selectedIndex].value;
                if (type ==='Espèce'){
                    total.value=parseFloat(total.value)+parseFloat(sommeCout(id_total,element.id));
                    console.log(total.value);
                }
            }
            total_espece.value=parseFloat(total_espece.value)+parseFloat(total.value);
        }
    }
</script>
<script src="https://code.jquery.com/jquery-3.3.1.slim.min.js"
        integrity="sha384-q8i/X+965DzO0rT7abK41JStQIAqVgRVzpbzo5smXKp4YfRvH+8abtTE1Pi6jizo"
        crossorigin="anonymous"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js"
        integrity="sha384-UO2eT0CpHqdSJQ6hJty5KVphtPhzWj9WO1clHTMGa3JDZwrnQq4sF86dIHNDz0W1"
        crossorigin="anonymous"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js"
        integrity="sha384-JjSmVgyd0p3pXB1rRibZUAYoIIy6OrQ6VrjIEaFf/nJGzIxFDsf4x0xIM+B07jRM"
        crossorigin="anonymous"></script>
</body>
</html>