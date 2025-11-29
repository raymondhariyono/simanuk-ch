function toggleSelect() {
   const tipe = document.getElementById('tipeAset').value;
   const divSarana = document.getElementById('selectSarana');
   const divPrasarana = document.getElementById('selectPrasarana');

   if (tipe === 'Sarana') {
      divSarana.classList.remove('hidden');
      divPrasarana.classList.add('hidden');
   } else {
      divSarana.classList.add('hidden');
      divPrasarana.classList.remove('hidden');
   }
}

function previewFile() {
   const input = document.getElementById('fileInput');
   const fileNameDisplay = document.getElementById('fileName');
   if (input.files && input.files[0]) {
      fileNameDisplay.textContent = 'File terpilih: ' + input.files[0].name;
      fileNameDisplay.classList.remove('hidden');

      // Optional styling for dropped file
      if (input.parentElement) {
         input.parentElement.classList.remove('border-dashed');
         input.parentElement.classList.add('border-solid', 'border-blue-300', 'bg-blue-50');
      }
   }
}