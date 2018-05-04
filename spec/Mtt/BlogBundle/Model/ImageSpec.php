<?php

namespace spec\Mtt\BlogBundle\Model;

use Mtt\BlogBundle\Entity\MediaFile;
use Mtt\BlogBundle\Model\Image;
use PhpSpec\ObjectBehavior;

class ImageSpec extends ObjectBehavior
{
    public function let()
    {
        $media = new MediaFile();
        $media
            ->setPath('blog/201311/debug.jpg')
            ->setFileSize(13)
        ;

        $this->beConstructedWith($media);
    }

    public function it_is_initializable()
    {
        $this->shouldHaveType(Image::class);
    }

    public function it_is_preview()
    {
        $this->getPreview('admin_list')->shouldReturn('blog/201311/0_60_debug.jpg');
    }

    public function it_is_incorrect_size()
    {
        $this->shouldThrow('\RuntimeException')->during('getPreview', ['n/a']);
    }

    public function it_is_file_size()
    {
        $this->getFileSize()->shouldReturn(13);
    }

    public function it_is_root_directory_file()
    {
        $this->getPathBySize('satan.png', 'admin_list')->shouldReturn('0_60_satan.png');
    }
}
