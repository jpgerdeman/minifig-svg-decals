# Does work on a decal
#
# The decal worker brings together the decal, which knows everything about its
# paths, and the execution.
class DecalWorker
	def setDecal(d)
		@decal = d
        self
    end
	
	# Copies a Decal from the source directory to the target directory
	def copySVG()
		FileUtils.copy(@decal.getSourcePath(), @decal.computeTargetPath())
	end

	# Renders a preview of the decal.
	#
	# the thumbnail renderer has to be set before calling this function
	# @see setThumbnailRenderer
	def renderThumbnail()	
		converter = @thumbnailRenderer
		converter.setInfile(@decal.getSourcePath()).setOutfile(@decal.computeThumbnailPath()).execute()
	end

	# Renders the decal.
	#
	# the png renderer has to be set before calling this function
	# @see setPngRenderer
	def renderPng()	
		converter = @pngRenderer;		
		converter.setInfile(@decal.getSourcePath()).setOutfile(@decal.computePngPath()).execute()
	end
	
	# Sets the renderer to be used for creating the thumbnails
	def setThumbnailRenderer(renderer)
		@thumbnailRenderer = renderer
		self
	end

	# Sets the renderer to be used
	def setPngRenderer(renderer)
		@pngRenderer = renderer
		self
	end
	
end
