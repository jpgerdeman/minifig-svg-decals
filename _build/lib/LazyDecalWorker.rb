# The lazy decal worker only executes, if a svg file has changed.
class LazyDecalWorker < DecalWorker

	# @inheritdoc	
	def setDecal(d)
		@changed = nil
		super.setDecal(d)
        self
    end
	
	# @inheritdoc
	# will ony execute if the svg file has changed
	def copySVG()
		if( haschanged() )
			super.copySVG()
		end
	end

	# @inheritdoc
	# will ony execute if the svg file has changed
	def renderThumbnail()	
		if( haschanged() )
			super.renderThumbnail()
		end
	end

	# @inheritdoc
	# will ony execute if the svg file has changed
	def renderPng()	
		if( haschanged() )
			super.renderPng()
		end
	end

	# Return wether a decal has changed or not.
	def haschanged()
		# Only compute the difference in time once. Since it compares the svg files
		# it would return false after the svg file has been copied.
		if( @changed == nil )
			@changed = !File.compare(@decal.getSourcePath(),@decal.computeTargetPath())
		end
		@changed
	end
end