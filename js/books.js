const aletr_element = document.querySelector(".alert");
const aletr_content = aletr_element.textContent.trim();

const getAlert = () => {
  switch (aletr_content) {
    case "Book title is empty":
    case "Book title must be between 3 to 255 character":
    case "Book category is empty":
    case "Book cover is empty":
    case "Book cover type is wrong":
    case "Book cover is very small":
    case "Book file is empty":
    case "Book file type is wrong":
    case "Book content is empty":
    case "Book content is must be between 50 to 20,000 character":

        aletr_element.classList.add("alert-warning", "d-block");

        setTimeout(() => {
            aletr_element.classList.remove("alert-warning", "d-block");
            aletr_element.classList.add("d-none");
            aletr_element.textContent = "";

        }, 3000);
        break;
  
    case "Error, Book can not be add":
    case "Error, Book can not be update":
    case "Error, cant't delete this Book write now":
    case "Error, comment can't be deleted":
    case "You had commented before":
    case "Comment is empty":
    case "Error storing comment, please try again":
    case "Error updating comment, please try again":

        aletr_element.classList.add("alert-danger", "d-block");

        setTimeout(() => {
            aletr_element.classList.remove("alert-danger", "d-block");
            aletr_element.classList.add("d-none");
            aletr_element.textContent = "";

        }, 3000);
        break;
  
    case "Book added successfully":
    case "Book updated successfully":
    case "Book deleted successfully":
    case "Comment has deleted successfully":
    case "Comment is stored successfully":
    case "Comment is updated successfully":

        aletr_element.classList.add("alert-success", "d-block");
        console.log("first")
        setTimeout(() => {
            aletr_element.classList.remove("alert-success", "d-block");
            aletr_element.classList.add("d-none");
            aletr_element.textContent = "";

            if (aletr_content === "Book deleted successfully" || aletr_content === "Book updated successfully" || aletr_content === "Book added successfully") { 
                window.location.href = "http://localhost/library/book-actions.php";
            }
        }, 3000);
        break;
  
    default:
        aletr_element.classList.remove("alert-success", "alert-danger", "alert-warning", "d-block");
        aletr_element.classList.add("d-none");
        aletr_element.textContent = "";
    }
}

getAlert();

// ===================================================================

const texterea = document.querySelector(".texterea");
const new_value = texterea.innerHTML.trim();
texterea.innerHTML = new_value;

