
 $(document).ready(function(){
        $(".join").click(function(){
            console.log("ok");
            var joined = $(this).attr('alt');
            console.log(JSON.stringify(joined));
            $.ajax({
                url: '/join_community',
                type: 'POST',
                dataType: 'JSON',
                data: {
                    myData :JSON.stringify(joined)
                },
                succes: function(data){
                    console.log("saved");
                }
            })
        });
    });