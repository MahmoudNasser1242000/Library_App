const aletr_element = document.querySelector(".alert");
const aletr_content = aletr_element.textContent.trim();

const getAlert = () => {
  switch (aletr_content) {
    case "warning, category name is empty!":

        aletr_element.classList.add("alert-warning", "d-block");

        setTimeout(() => {
            aletr_element.classList.remove("alert-warning", "d-block");
            aletr_element.classList.add("d-none");
            aletr_element.textContent = "";

        }, 3000);
        break;
  
    case "Error, Category can't be add!":
    case "Error, Category can't be update!":
    case "Error, cant't delete this category write now":

        aletr_element.classList.add("alert-danger", "d-block");

        setTimeout(() => {
            aletr_element.classList.remove("alert-danger", "d-block");
            aletr_element.classList.add("d-none");
            aletr_element.textContent = "";

        }, 3000);
        break;
  
    case "Category added successfully":
    case "Category updated successfully":
    case "Category deleted successfully":

        aletr_element.classList.add("alert-success", "d-block");
        console.log("first")
        setTimeout(() => {
            aletr_element.classList.remove("alert-success", "d-block");
            aletr_element.classList.add("d-none");
            aletr_element.textContent = "";

            window.location.href = "http://localhost/library/categories.php";
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
