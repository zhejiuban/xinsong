<?php

namespace App\Http\Controllers\Plugin;

use App\File as FileModel;
use App\Services\ResponseJsonMessageService;
use Intervention\Image\Facades\Image;
use Storage;
use Validator;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class FileController extends Controller
{
    use ResponseJsonMessageService;

    //图片上传接口
    public function imageUpload(Request $request)
    {
        $image_upload_config = config('filesystems.disks.image');
        //上传验证
        $rule = [
            'file' => 'bail|required|image|max:' . $image_upload_config['validate']['size'] . '|mimes:' . arr2str($image_upload_config['validate']['ext'])
        ];
        $message = [
            'file.required' => '请选择上传文件',
            'file.image' => '上传文件必须是图片',
            'file.max' => '上传图片大小不能大于' . format_bytes($image_upload_config['validate']['size'] * 1024),
            'file.mimes' => '上传图片类型不符合'
        ];
        $file = $request->file();
        $validator = Validator::make($file, $rule, $message);
        if ($validator->fails()) {
            self::setStatus(0);
            self::setMessage($validator->errors()->first('file'));
        } else {
            //上传图片
            $upload_file = $file['file'];
            if ($upload_file->isValid()) {
                //设置保存目录
                $save_path = $image_upload_config['upload_path_format'];
                //文件的扩展名
                $ext = $upload_file->getClientOriginalExtension();
                $uniqid = uniqid();
                //设置保存文件名
                $save_name = $uniqid . '.' . $ext;
                //文件转存
                $new_file = $upload_file->move($image_upload_config['root'] . '/'
                    . $save_path, $save_name);
                $path = $image_upload_config['base_path'] . '/' . $save_path . '/' . $save_name;
                //缩略图处理
                if ($ext != 'gif') {
                    // 此类中封装的函数，用于裁剪图片
                    $this->reduseSize($path, config('filesystems.disks.image.thumbnail.max_width'));
                }

                //数据库保存上传文件信息
                $file_info = new FileModel();
                $file_info->type = $upload_file->getClientMimeType();
                $file_info->name = $save_name;
                $file_info->old_name = $upload_file->getClientOriginalName();
                $file_info->width = 0;
                $file_info->height = 0;
                $file_info->suffix = $ext;
                $file_info->file_path = $new_file->getRealPath();
                $file_info->path = $path;
                $file_info->url = asset($path);
                $file_info->size = $upload_file->getClientSize();
                $file_info->ip = $request->ip();
                $file_info->user_id = get_current_login_user_info();
                $file_info->upload_mode = 'image';
                $file_info->uniqid = $uniqid;

                if ($file_info->save()) {
                    self::setStatus(1);
                    self::setData($file_info->toArray());
                    self::setMessage('上传成功');
                } else {
                    self::setStatus(0);
                    self::setMessage('上传失败');
                }
            } else {
                self::setStatus(0);
                self::setMessage('上传失败！请联系管理员');
            }
        }
        return response()->json(self::getMessageResult());
    }

    //文件上传接口
    public function fileUpload(Request $request)
    {
        $file_upload_config = config('filesystems.disks.file');
        //上传验证
        $rule = [
            'file' => 'bail|required|max:' . $file_upload_config['validate']['size'] . '|mimes:' . arr2str($file_upload_config['validate']['ext'])
        ];
        $message = [
            'file.required' => '请选择上传文件',
            'file.max' => '上传文件大小不能大于' . format_bytes($file_upload_config['validate']['size'] * 1024),
            'file.mimes' => '上传文件类型不符合'
        ];
        $file = $request->file();
        $validator = Validator::make($file, $rule, $message);
        if ($validator->fails()) {
            self::setStatus(0);
            self::setMessage($validator->errors()->first('file'));
        } else {
            //上传图片
            $upload_file = $file['file'];
            if ($upload_file->isValid()) {
                $disk = Storage::disk('file');
                //设置保存目录
                $save_path = $file_upload_config['upload_path_format'];
                //文件的扩展名
                $ext = $upload_file->getClientOriginalExtension();
                $uniqid = uniqid();
                //设置保存文件名
                $save_name = $uniqid . '.' . $ext;
                $path = $file_upload_config['base_path'] . '/' . $save_path . '/' . $save_name;
                //文件转存
                $bool = $disk->putFileAs($save_path, $upload_file, $save_name);
                //数据库保存上传文件信息
                $file_info = new FileModel();
                $file_info->type = $upload_file->getClientMimeType();
                $file_info->name = $save_name;
                $file_info->old_name = $upload_file->getClientOriginalName();
                $file_info->suffix = $ext;
                $file_info->file_path = public_path() . '/' . $path;
                $file_info->path = $path;
                $file_info->url = asset($path);
                $file_info->size = $upload_file->getClientSize();
                $file_info->ip = $request->ip();
                $file_info->user_id = get_current_login_user_info();
                $file_info->upload_mode = 'file';
                $file_info->uniqid = $uniqid;

                if ($bool && $file_info->save()) {
                    self::setStatus(1);
                    self::setData($file_info->toArray());
                    self::setMessage('上传成功');
                } else {
                    self::setStatus(0);
                    self::setMessage('上传失败');
                }
            } else {
                self::setStatus(0);
                self::setMessage($upload_file->getErrorMessage());
            }
        }
        return response()->json(self::getMessageResult());
    }

    //视频上传接口
    public function videoUpload()
    {

    }

    //base64上传接口
    public function base64Upload()
    {

    }

    /**
     * 文件下载
     * @param $id
     */
    public function download($id)
    {
        $file = FileModel::where('uniqid',$id)->first();
        if($file){
            return response()->download(public_path($file->path), $file->old_name);
        }
    }

    public function reduseSize($path, $max_width)
    {
        // 先实例化，传参是文件的磁盘物理路径
        $image = Image::make($path);

        // 进行大小调整的操作
        $image->resize($max_width, null, function ($constraint) {

            // 设定宽度是 $max_width，高度等比例双方缩放
            $constraint->aspectRatio();

            // 防止裁图时图片尺寸变大
            $constraint->upsize();
        });

        // 对图片修改后进行保存
        $image->save();
    }
}
