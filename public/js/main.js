// JS Document

if(document.getElementById("resetForm")) {
   const resetForm = document.getElementById("resetForm");

   resetForm.addEventListener("click", function(event) {
      let conf = confirm("Confirmer-vous la suppression ? Cette action est irréversible.");

      if(conf == true) {
         // We validate the sending of the form
         this.form.submit();
      }
   });
}