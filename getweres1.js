var select = document.getElementById("projet");
var adds =document.getElementsByClassName('add');
var totals = document.getElementsByClassName('total');
var payments = document.getElementsByClassName('payment');
var couts = document.getElementsByClassName('cout');
function infos() {
    var d = new Date();
    var year = d.getFullYear().toString().substring(2,4) ;
    var projet=select.options[select.selectedIndex].text;
    var num = document.getElementById("numProjet").value ;
    var month =d.getMonth();
    if (num<10){
        num = "0"+num ;
    }
    month++;
    if(month<10){
        month="0"+month ;
    }
    var num_and_month=num+month;
    infos = document.getElementById("FM_infos") ;
    console.log(infos);
    infos.innerHTML="FM_"+projet+"_"+num_and_month+"_"+year;
    document.getElementById('FM_id').value=infos.innerHTML;
}
function payment(num){
    var payments = document.getElementsByClassName('payment');
    var tableau = Array();
    for (var payment of payments) {
        var id = payment.id ;
        var length=id.length;
        if(id.substring(length-1,length)===num){
            tableau.push(payment);
        }
    }
    return(tableau);
}
function cout(num){
    couts = document.getElementsByClassName('cout');
    var tableau = Array();
    for (var cout of couts) {
        var id = cout.id ;
        var length=id.length;
        if(id.substring(length-1,length)===num){
            tableau.push(cout);
        }
    }
    return(tableau);
}
function sommeCout(num,id){
    var tableau =cout(num);
    for (var element of tableau) {
        (element.value!="")?valeur=element.value:valeur=0;
        var id_cout="cout"+id.substring(8,id.length);
        if(id_cout===element.id){
            return valeur;
        }
    }
    return 0 ;
}
function total(){
    for (var pay of payments) {
        pay.addEventListener('change',function(e){
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
                    console.log(type);
                    if (type ==='Espèce'){
                        total.value=parseFloat(total.value)+parseFloat(sommeCout(id_total,element.id));
                        console.log(total.value);
                    }
                }
                total_espece.value=parseFloat(total_espece.value)+parseFloat(total.value);
            }

        });
    }
}
function addLines(tableaux){
    for (var tableau of tableaux) {
        addLine(tableau);
    }
    lines = document.getElementById('add_lines');
    if(lines.value===""){lines.value=0;}
    lines.value= parseInt(lines.value)+1;
}

function addLine(tableau){
    var row = tableau.rows[1].cells;
    var length = tableau.rows.length ;
    var Line = tableau.insertRow(length);
    var i=0 ;
    for (var cell of row) {
        var newcell = Line.insertCell(i);
        i++;
        newcell.innerHTML=cell.innerHTML;
        newcell=newcell.firstElementChild;
        var oldname = newcell.name;
        newcell.name = oldname.substring(0,oldname.length-1)+length;
        newcell.id =newcell.name ;
    }
}
total();
infos();
for (var x of adds) {
    x.addEventListener('click',function(){
        tabledescription =document.getElementById('description');
        tablecarburant   =document.getElementById('carburant');
        tablepeage       =document.getElementById('peage');
        tablerepas       =document.getElementById('repas');
        tablehotel       =document.getElementById('hotel');
        tableautres      =document.getElementById('autres');
        tabletotal       =document.getElementById('total');
        var tableaux = new Array(tablecarburant,tabledescription,tableautres,tablehotel,tablepeage,tablerepas,tabletotal);
        addLines(tableaux);
        totals = document.getElementsByClassName('total');
        payments= document.getElementsByClassName('payment');
        total();
    })
}

select.addEventListener('click',function(){
    var d = new Date();
    var year = d.getFullYear().toString().substring(2,4) ;
    var projet=select.options[select.selectedIndex].text;
    var num = document.getElementById("numProjet").value ;
    var month =d.getMonth();
    if (num<10){
        num = "0"+num ;
    }
    month++;
    if(month<10){
        month="0"+month ;
    }
    var num_and_month=num+month;
    infos = document.getElementById("FM_infos") ;
    console.log(infos);
    infos.innerHTML="FM_"+projet+"_"+num_and_month+"_"+year;
    document.getElementById('FM_id').value=infos.innerHTML;
});
document.getElementById("driveradd").addEventListener('click',function (e) {
    var tableau = document.getElementById('drivertable');
    var row = tableau.rows[1].cells
    console.log(row);
    var length = tableau.rows.length;
    var Line = tableau.insertRow(length);
    var i = 0;
    for (var cell of row) {
        var newcell = Line.insertCell(i);
        i++;
        newcell.innerHTML = cell.innerHTML;
        console.log(newcell);
        newcell = newcell.firstElementChild;
        var oldname = newcell.id;
        console.log(oldname)
        newcell.name = oldname.substring(0, oldname.length - 1) + length;
        newcell.id = newcell.name;
    }
});
