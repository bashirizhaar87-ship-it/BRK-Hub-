<?php
include "../includes/header.php";
?>

<div class="container mt-4">

<div class="card bg-dark border-primary">

<div class="card-body">

<h2 class="text-center mb-4">
📤 Upload File
</h2>

<form enctype="multipart/form-data">

<div class="mb-3">
<label>Title</label>
<input
type="text"
class="form-control"
placeholder="File Title">
</div>

<div class="mb-3">
<label>Category</label>

<select class="form-select">

<option>APK</option>

<option>Song</option>

<option>Video</option>

</select>

</div>

<div class="mb-3">
<label>Version</label>

<input
type="text"
class="form-control"
placeholder="1.0.0">

</div>

<div class="mb-3">
<label>Description</label>

<textarea
class="form-control"
rows="4"
placeholder="Write something..."></textarea>

</div>

<div class="mb-3">
<label>Select File</label>

<input
type="file"
class="form-control">

</div>

<div class="d-grid">

<button
class="btn btn-success">

Upload

</button>

</div>

</form>

</div>

</div>

</div>

<?php
include "../includes/footer.php";
?>