<h1>Ajax Demo</h1>

<div id="page2-section1">
    <p>This is the content of Page 2. You can add more information here.</p>
<?= nl2br('Sed at dolor leo. Morbi a tellus sed nisl dictum ultricies sit amet at purus. Nam mattis metus sed nunc egestas convallis. Fusce sagittis tellus convallis sem volutpat, a aliquam nulla posuere. Lorem ipsum dolor sit amet, consectetur adipiscing elit. Sed malesuada, nunc at iaculis tempus, augue sem venenatis augue, nec hendrerit dolor arcu tempus tortor. Proin euismod nunc mi, ut ultrices elit porttitor et.

Aliquam erat volutpat. Ut non purus sit amet orci pulvinar elementum. Curabitur pharetra mi eget viverra sagittis. Maecenas consequat metus vitae gravida commodo. Sed vitae neque sed massa ultricies convallis. Duis venenatis, lacus non volutpat aliquam, augue elit aliquam odio, vitae iaculis tortor urna rhoncus magna. Morbi lacus dui, egestas vitae turpis quis, volutpat aliquam purus. Fusce in libero sapien. Pellentesque quis neque eget mauris scelerisque tempus quis iaculis arcu. Duis sollicitudin sit amet magna non finibus. Donec hendrerit erat vel nunc scelerisque viverra. Donec et elementum augue, eget fringilla arcu.') ?>
    
    <br/>

    <button onclick="$(this).parent().load('<?= URL::Link('page2-section1') ?>')">
        Load new text
    </button>

</div>
