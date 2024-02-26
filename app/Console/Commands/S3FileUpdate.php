<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
use App\Traits\S3ConfigTrait;
use App\Helpers\Helper;
use Illuminate\Support\Facades\Storage;

class S3FileUpdate extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 's3fileupload:update';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Upload the server uploads directory & its files to the s3 bucket of testing IDs on the Live Server';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $s3_config = S3ConfigTrait::s3Config();

        if($s3_config!=NULL)
        {
            $file_platform = 's3';

            // JAF Details Attachment

            $s3filePath='uploads/jaf_details/';

            $path = public_path()."/uploads/jaf_details/";

            if(!Storage::disk('s3')->exists($s3filePath))
            {
                Storage::disk('s3')->makeDirectory($s3filePath,0777, true, true);
            }

            $jaf_files = DB::table('jaf_files')->whereIn('parent_id',[94])->where('file_platform','web')->get();

            if(count($jaf_files)>0)
            {
                foreach($jaf_files as $jaf_file)
                {
                    if(File::exists($path.$jaf_file->zip_file))
                    {
                        $zip_file = Helper::createFileObject($path.$jaf_file->zip_file);

                        Storage::disk('s3')->put($s3filePath.$jaf_file->zip_file,file_get_contents($zip_file));

                        DB::table('jaf_files')->where('id',$jaf_file->id)->update(['file_platform'=>$file_platform]);

                        File::delete($path.$jaf_file->zip_file);

                    }

                    if(File::exists($path.$jaf_file->file_name))
                    {
                        $file = Helper::createFileObject($path.$jaf_file->file_name);

                        Storage::disk('s3')->put($s3filePath.$jaf_file->file_name,file_get_contents($file));

                        DB::table('jaf_files')->where('id',$jaf_file->id)->update(['file_platform'=>$file_platform]);

                        File::delete($path.$jaf_file->file_name);
                    }
                }
            }

            // JAF File Attachments

            $s3filePath='uploads/jaf-files/';

            $path = public_path()."/uploads/jaf-files/";

            if(!Storage::disk('s3')->exists($s3filePath))
            {
                Storage::disk('s3')->makeDirectory($s3filePath,0777, true, true);
            }

            $jaf_file_attachments = DB::table('jaf_item_attachments as jfa')
                                    ->select('jfa.*')
                                    ->join('users as u','u.id','=','jfa.candidate_id')
                                    ->where('jfa.file_platform','web')
                                    ->whereIn('u.parent_id',[94])
                                    ->where('u.user_type','candidate')
                                    ->get();

            if(count($jaf_file_attachments)>0)
            {
                foreach($jaf_file_attachments as $jaf_file)
                {
                    if(File::exists($path.$jaf_file->file_name))
                    {
                        $file = Helper::createFileObject($path.$jaf_file->file_name);

                        Storage::disk('s3')->put($s3filePath.$jaf_file->file_name,file_get_contents($file));

                        DB::table('jaf_item_attachments')->where('id',$jaf_file->id)->update(['file_platform'=>$file_platform]);

                        File::delete($path.$jaf_file->file_name);
                    }
                }
            }

            // Report File Attachment

            $s3filePath='uploads/report-files/';

            $path = public_path()."/uploads/report-files/";

            if(!Storage::disk('s3')->exists($s3filePath))
            {
                Storage::disk('s3')->makeDirectory($s3filePath,0777, true, true);
            }

            $report_item_attachments = DB::table('report_item_attachments as ra')
                                            ->select('ra.*')
                                            ->join('reports as r','r.id','=','ra.report_id')
                                            ->whereIn('r.parent_id',[94])
                                            ->where('ra.file_platform','web')
                                            ->get();

            if(count($report_item_attachments)>0)
            {
                foreach($report_item_attachments as $report_file)
                {
                    if(File::exists($path.$report_file->file_name))
                    {
                        $file = Helper::createFileObject($path.$report_file->file_name);

                        Storage::disk('s3')->put($s3filePath.$report_file->file_name,file_get_contents($file));

                        DB::table('report_item_attachments')->where('id',$report_file->id)->update(['file_platform'=>$file_platform]);

                        File::delete($path.$report_file->file_name);
                    }
                }
            }

            $this->info('File has been uploaded to s3 bucket at '.date('Y-m-d h:i A'));

        }

        return 0;
    }
}
