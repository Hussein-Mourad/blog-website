function showReplyForm(form)
{  
    console.log(form);
    if (form.style.display === "none") {
        form.style.display = "block";
      } else {
        form.style.display = "none";
      }
}