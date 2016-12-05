    </div>
    <footer class="text-center" id="footer"><font color="#fff">&copy; Copyright 2016 SHOPPER CENTRAL</font></footer>

    <script>
        function updateSizes(){
            var sizeString = '';
            for(var i=1;i<=12;i++){
                if($('#size'+i).val() != ''){
                    sizeString += $('#size'+i).val()+':'+$('#qty'+i).val()+',';
                }
            }
            $('#sizes').val(sizeString);
        }

        function get_child_options(selected){
            if(typeof selected === 'undefined'){
              var selected = '';
            }
            var parentID = $('#parent').val();
            jQuery.ajax({
                url: 'parsers/child_categories.php',
                type: 'POST',
                data: {parentID : parentID, selected: selected},
                success: function(data){
                    $('#child').html(data);
                },
                error: function(){alert("Something went wrong with the child options.")},
            });
        }
        $('select[name="parent"]').change(function(){
          get_child_options();
        });
    </script>
    </body>
</html>
