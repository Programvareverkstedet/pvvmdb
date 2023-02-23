const table = document.getElementById("userTable");
const searchInput = document.getElementById("searchInput");

// Load all users from table
const users = [];
for (let i = 1; i < table.rows.length; i++) {
  const row = table.rows[i];
  const username = row.cells[0].innerText;
  const name = row.cells[1].innerText;

  users.push({
    username: username,
    name: name,
    row: row
  });
}

// Create fuse instance
const fuse = new Fuse(users, {
  keys: ["username", "name"],
  threshold: 0.3
});

// Search function
function search(query) {
  if (query.length == 0) {
    // Empty search query, display all users
    for (let i = 0; i < users.length; i++) {
      users[i].row.style.display = "";
    }
    return
  }

  const results = fuse.search(query);

  // Hide/Show all rows, depending on if they are in the results
  for (let i = 1; i < table.rows.length; i++) {
    if (results.find(result => result.item.row === table.rows[i])) {
      table.rows[i].style.display = "";
    } else {
      table.rows[i].style.display = "none";
    }
  }
}

let timeout = null;
searchInput.oninput = (e) => {
  if (timeout) clearTimeout(timeout);

  timeout = setTimeout(() => {
    search(e.target.value);
  });
}
