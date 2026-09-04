<textarea id="{{ $id ?? 'editor' }}" name="{{ $name ?? 'description' }}">
    {{ $slot }}
</textarea>

@once
    <script src="https://cdn.tiny.cloud/1/sohw9vxk30kc7dovlwwjhfqiwan2jr4x6nzw9z71ogqc9dof/tinymce/8/tinymce.min.js"
        referrerpolicy="origin" crossorigin="anonymous"></script>
@endonce

<script>
    tinymce.init({
        selector: 'textarea',
        plugins: [
      "advlist", "anchor", "autolink", "charmap", "code", "fullscreen",
      "help", "image", "insertdatetime", "link", "lists", "media",
      "preview", "searchreplace", "table", "visualblocks",
  ],
  toolbar: "undo redo | styles | bold italic underline strikethrough | alignleft aligncenter alignright alignjustify | bullist numlist outdent indent | link image",
  uploadcare_public_key: 'f77ab31c874fb727da99',
    
    });
</script>
