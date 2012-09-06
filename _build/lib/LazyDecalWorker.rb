# The lazy decal worker only executes, if a svg file has changed.
class LazyDecalWorker

	def initialize( worker )
		@worker = worker
	end

	# @inheritdoc	
	def setDecal(d)
		@changed = nil
		@decal = d
		@worker.setDecal(d)

		has = haschanged()? 'has' : 'has not'
		puts "#{@decal.getSourcePath()} #{has} changed "

        self
    end
	
	# @inheritdoc
	# will ony execute if the svg file has changed
	def copySVG()
		if( haschanged() )
			@worker.copySVG()
		end
	end

	# @inheritdoc
	# will ony execute if the svg file has changed
	def renderThumbnail()	
		if( haschanged() )
			@worker.renderThumbnail()
		end
	end

	# @inheritdoc
	# will ony execute if the svg file has changed
	def renderPng()	
		if( haschanged() )
			@worker.renderPng()
		end
	end

	# Return wether a decal has changed or not.
	def haschanged()
		# Only compute the difference in time once. Since it compares the svg files
		# it would return false after the svg file has been copied.
		if( @changed == nil )
			if( File.exists?( @decal.computeTargetPath()) )
				@changed = !FileUtils.cmp(@decal.getSourcePath(),@decal.computeTargetPath())
			else
				@changed = true
			end
		end
		@changed
	end
end