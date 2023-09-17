const search_input = document.querySelector(".search-input");

const search_email = document.querySelector(".search-email");
const search_name = document.querySelector(".search-name");

const tb_body = document.querySelector(".tb-body");

const user_name = document.querySelectorAll(".user-name");
const user_email = document.querySelectorAll(".user-email");

// search with name
search_name.addEventListener("click", (e) => {
  e.preventDefault();

  if (search_input.value !== "") {
    tb_body.innerHTML = "";

    user_name.forEach((name) => {
      if (name.textContent.includes(search_input.value)) {
        tb_body.innerHTML += name.parentElement.innerHTML;
      }
    });

  } else {
    window.location.href = "http://localhost/library/all-users.php";
  }

});

// search with email
search_email.addEventListener("click", (e) => {
  e.preventDefault();

  if (search_input.value !== "") {
    tb_body.innerHTML = "";

    user_email.forEach((email) => {
      if (email.textContent.includes(search_input.value)) {
        tb_body.innerHTML += email.parentElement.innerHTML;
      }
    });
    
  } else {
    window.location.href = "http://localhost/library/all-users.php";
  }
});

// =================================================================================

const blocked_user = document.querySelector(".blocked-user");
const unblocked_user = document.querySelector(".unblocked-user");
const all_users = document.querySelector(".all-users");

const allow = document.querySelectorAll(".allow");

// blocked users
blocked_user.addEventListener("click", (e) => {
  tb_body.innerHTML = "";

  allow.forEach((allow) => {
    if (allow.innerHTML.trim() === "true") {
      tb_body.innerHTML += allow.parentElement.innerHTML;
    }
  });
});

// unblocked users
unblocked_user.addEventListener("click", (e) => {
  tb_body.innerHTML = "";

  allow.forEach((allow) => {
    if (allow.innerHTML.trim() === "false") {
      tb_body.innerHTML += allow.parentElement.innerHTML;
    }
  });
});

// all users
all_users.addEventListener("click", (e) => {
  window.location.href = "http://localhost/library/all-users.php";
});
