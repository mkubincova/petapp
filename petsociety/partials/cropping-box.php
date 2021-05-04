   <div class="modal fade" id="modal" tabindex="-1" role="dialog" aria-labelledby="modalLabel" aria-hidden="true">
       <div class="modal-dialog modal-lg" role="document">
           <div class="modal-content">
               <div class="modal-header">
                   <h5 class="modal-title" id="modalLabel">Crop image</h5>
                   <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                       <span aria-hidden="true">×</span>
                   </button>
               </div>
               <div class="modal-body">
                   <div class="img-container">
                       <div class="row">
                           <div class="col-md-8">
                               <!--  default image where we will set the src via jquery-->
                               <img id="image">
                           </div>
                           <div class="col-md-4">
                               <div class="preview"></div>
                           </div>
                       </div>
                   </div>
               </div>
               <div class="modal-footer">
                   <button type="button"  data-dismiss="modal" class="cancel-btn">Cancel</button>
                   <button type="button"  id="crop">Crop</button>
               </div>
           </div>
       </div>
   </div>