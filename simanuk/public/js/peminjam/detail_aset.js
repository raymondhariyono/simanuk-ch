function changeImage(src) {
   const mainImage = document.getElementById('mainImage');
   if (mainImage) {
      // Optional: Add fade effect
      mainImage.style.opacity = 0.5;
      setTimeout(() => {
         mainImage.src = src;
         mainImage.style.opacity = 1;
      }, 150);
   }
}