function c(){
    var r= new FileReader();
    f=document.getElementById('file').files[0];

    r.readAsDataURL(f);
    r.onload=function (e) {
        document.getElementById('show').src=this.result;
    };
}