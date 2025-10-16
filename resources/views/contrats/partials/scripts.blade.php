<script>
document.querySelectorAll('.removeRow').forEach(btn => {
    btn.addEventListener('click', function () {
        this.closest('tr').remove();
    });
});

document.getElementById('addContractant').addEventListener('click', function () {
    let select = document.getElementById('selectContractant');
    let id = select.value;
    let text = select.options[select.selectedIndex].text;

    let tbody = document.getElementById('contractantsTable');
    let row = document.createElement('tr');
    row.innerHTML = `
        <td>
            <input type="hidden" name="contractants[]" value="${id}">
            ${text}
        </td>
        <td><button type="button" class="btn btn-danger btn-sm removeRow">❌</button></td>
    `;
