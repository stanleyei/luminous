<!-- 所見及所得文字編輯器 -->

<script setup>
import 'tinymce/tinymce';
import 'tinymce/themes/silver';
import 'tinymce/models/dom';
import 'tinymce/icons/default';
import 'tinymce/skins/ui/oxide/skin.min.css';
import 'tinymce/skins/ui/oxide/content.inline.min.css';
import 'tinymce/skins/content/default/content';
import 'tinymce/plugins/autolink';
import 'tinymce/plugins/link';
import 'tinymce/plugins/image';
import 'tinymce/plugins/lists';
import 'tinymce/plugins/table';
import 'tinymce/plugins/code';
import 'tinymce/plugins/media';
import 'tinymce/plugins/nonbreaking';
import '@/Plugin/tinymce/langs/zh-Hant';
import { router } from '@inertiajs/vue3';
import editorCss from '/css/editor.css?inline';
import editor from '@tinymce/tinymce-vue';

const props = defineProps({
  modelValue: {
    type: String,
    required: false,
    default: '',
  },
  editorOption: {
    type: Object,
    required: false,
    default: () => {
      return {
        image: false,
        media: false,
        resize: false,
        code: false,
      };
    },
  },
  imgUploadUrl: {
    type: String,
    required: false,
    default: '',
  },
  imgUploadDir: {
    type: String,
    required: false,
    default: '',
  },
});

const emit = defineEmits(['update:modelValue']);
const editorValue = computed({
  get: () => props.modelValue,
  set: (value) => emit('update:modelValue', value),
});
const editorInit = computed(() => ({
  plugins: 'autolink link image lists table code media nonbreaking', // 插件
  height: '100%', // 高度
  language: 'zh-Hant', // 語言
  branding: false, // 是否開啟右下角的官方文字
  promotion: false, // 是否開啟右上角的廣告文字
  menubar: 'file edit table format', // 頂部選單的內容
  menu: {
    format: {
      title: '格式',
      items: 'bold italic underline strikethrough superscript subscript removeformat | fontfamily fontsize align lineheight | forecolor backcolor',
    },
  }, // 頂部選單下拉的細項內容
  resize: props.editorOption?.resize ?? false, // 是否開啟自動調整大小的工具
  images_upload_url: '', // 放入上傳檔案的後台路徑，如果有設images_upload_handler，此處就不影響
  convert_urls: false, // 是否自動轉換路徑
  file_picker_types: 'image', // 限制檔案上傳的格式
  default_link_target: '_blank', // 預設開啟連結的方式
  nonbreaking_force_tab: true, // 是否開啟tab鍵的功能
  target_list: [
    { title: '開新視窗', value: '_blank' },
  ], // 開啟連結的方式的選單
  skin: false,
  content_style: editorCss, // 編輯器的css樣式
  toolbar: [], // 工具列
  font_size_formats: '0.75rem 0.875rem 1rem 1.125rem 1.25rem 1.5rem 2.25rem', // 設定字體單位
  iframe_template_callback: (data) => {
    return `<iframe title="外部影片" width="${data.width}" height="${data.height}" src="${data.source}"></iframe>`;
  }, // 設定外部影片的iframe
  setup: function (editor) {
    editor.on('PreInit', function () {
      editor.getBody().style.fontSize = '1rem';
    });
  },
  images_upload_handler: async (blobInfo) => {
    const img = `data:${blobInfo.blob().type};base64,${blobInfo.base64()}`;
    const res = await new Promise((resolve) => {
      router.post(props.imgUploadUrl, { img, dir: props.imgUploadDir }, {
        preserveState: true,
        preserveScroll: true,
        onSuccess: ({ props: { flash: { message = {} } = {} } = {} } = {}) => {
          if (!message || !message?.rt_code) return;
          const rtData = message.rt_data ?? '';
          resolve(rtData);
        },
      });
    });
    return res;
  },
}));

// 設定工具列
const setToolbar = () => {
  const imageTool = props.editorOption.image ? 'image' : '';
  const codeTool = props.editorOption.code ? 'code' : '';
  const mediaTool = props.editorOption.media ? 'media' : '';
  editorInit.value.toolbar = [
    `undo redo | bullist outdent indent | bold italic | link ${imageTool} ${mediaTool} ${codeTool} | alignleft aligncenter alignright alignjustify | superscript subscript | forecolor backcolor | table hr`,
  ];
};

setToolbar();
</script>

<template>
  <editor v-model="editorValue" :init="editorInit" />
</template>
