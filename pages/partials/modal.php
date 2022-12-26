<?php
if(isset($data['error'])){
?>

<div class="modal pageActive" >
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLabel">Modal title</h5>
        
      </div>
      <div class="modal-body">

        <?php
        
            foreach($data['error'] as $item){

                if(is_array($item)){

                    foreach($item as $nestedItem){
                        echo $nestedItem;
                        echo "<br>";
                    }
                }else{

                    echo $item;
                    echo "<br>";
                }
    
            }
     ?>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary modal-close-error" >Close</button>
      </div>
    </div>
  </div>
</div>


<script>
let btnColose = document.querySelector(".modal-close-error")
let modal = document.querySelector(".modal")

btnColose.onclick = ()=>{
        modal.classList.remove("pageActive")
        modal.classList.add("pagedeActive")

}
</script>

<?php
}

?>
