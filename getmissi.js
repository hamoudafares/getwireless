var button_missions = document.getElementsByClassName('delete_mission');
for (var button of button_missions){
    button.addEventListener('click',function (e) {
        var result = confirm("Are you sure to delete?");
        if(result){
            console.log("deleted!");
        }else {
            console.log("canceled");
            e.preventDefault();
        }
    })
}