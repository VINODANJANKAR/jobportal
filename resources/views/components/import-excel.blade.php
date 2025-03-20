<form action="{{ $action }}" method="POST" enctype="multipart/form-data" >
    @csrf
    <div class="input-group">
        <input type="file" name="file" class="form-control text-dark border-light form-control-sm" accept=".xls, .xlsx, .csv" required>
        <button type="submit" class="btn btn-success btn-sm  text-dark border-light">Import Excel</button>
    </div>
</form>
